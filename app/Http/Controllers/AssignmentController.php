<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $this->schoolId($request);
        $user = $request->user();

        $query = Assignment::with(['subject', 'class'])
            ->where('school_id', $schoolId);

        $collectedAssignments = collect();

        if ($user->isStudent()) {
            $student = $user->student;
            if ($student) {
                $query->where(fn ($q) => $q->whereNull('class_id')->orWhere('class_id', $student->class_id))
                    ->with(['submissions' => fn ($q) => $q->where('student_id', $student->id)]);

                $collectedAssignments = (clone $query)
                    ->latest()
                    ->get()
                    ->filter(fn ($a) => $a->submissions->isNotEmpty())
                    ->values();
            }
        }

        $assignments = $query->withCount('submissions')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('assignments.index', compact('assignments', 'collectedAssignments'));
    }

    public function create(Request $request)
    {
        $schoolId = $this->requireSchool($request);

        return view('assignments.create', $this->formOptions($schoolId));
    }

    public function store(Request $request)
    {
        $schoolId = $this->requireSchool($request);
        $data = $this->validateAssignment($request);

        Assignment::create([
            'school_id' => $schoolId,
            'title' => $data['title'],
            'description' => $data['description'],
            'class_id' => $data['class_id'] ?? null,
            'subject_id' => $data['subject_id'] ?? null,
            'due_at' => $data['due_at'] ?? null,
            'teacher_id' => $request->user()->isTeacher() ? $request->user()->teacher?->id : null,
        ]);

        return redirect()->route('assignments.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function show(Request $request, Assignment $assignment)
    {
        $this->authorizeSchool($request, $assignment);
        $user = $request->user();

        $assignment->load(['subject', 'class', 'teacher']);

        $student = null;
        $submission = null;
        $students = collect();

        if ($user->isTeacher() || $user->isSuperAdmin() || $user->isSchoolAdmin()) {
            $assignment->load('submissions');

            $subByStudent = $assignment->submissions->keyBy('student_id');

            $students = $assignment->class_id
                ? Student::with('class')->where('class_id', $assignment->class_id)->orderBy('name')->get()
                : Student::with('class')->where('school_id', $assignment->school_id)->orderBy('name')->get();

            $students->each(fn ($s) => $s->setAttribute('submission', $subByStudent->get($s->id)));
        } elseif ($user->isStudent()) {
            $student = $user->student;
            $submission = $assignment->submissionFor($student);
        }

        return view('assignments.show', compact('assignment', 'student', 'submission', 'students'));
    }

    public function edit(Request $request, Assignment $assignment)
    {
        $this->authorizeSchool($request, $assignment);

        return view('assignments.edit', array_merge(
            ['assignment' => $assignment],
            $this->formOptions($assignment->school_id),
        ));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $this->authorizeSchool($request, $assignment);
        $data = $this->validateAssignment($request);

        $assignment->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'class_id' => $data['class_id'] ?? null,
            'subject_id' => $data['subject_id'] ?? null,
            'due_at' => $data['due_at'] ?? null,
        ]);

        return redirect()->route('assignments.show', $assignment)->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Request $request, Assignment $assignment)
    {
        $this->authorizeSchool($request, $assignment);

        $assignment->delete();

        return redirect()->route('assignments.index')->with('success', 'Tugas berhasil dihapus.');
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $this->authorizeSchool($request, $assignment);

        if (! $request->user()->isStudent()) {
            abort(403);
        }

        $student = $request->user()->student;
        abort_if(! $student, 403, 'Akun siswa belum terhubung ke data siswa.');

        if ($assignment->isLate()) {
            return back()->with('error', 'Batas waktu pengumpulan tugas telah lewat.');
        }

        $data = $request->validate([
            'content' => ['nullable', 'string', 'max:5000'],
            'file' => ['nullable', 'file', 'max:10240'],
        ]);

        if (blank($data['content'] ?? null) && ! $request->hasFile('file')) {
            return back()->withErrors(['content' => 'Tulis jawaban atau lampirkan file terlebih dahulu.'])->withInput();
        }

        $submission = $assignment->submissionFor($student);

        $filePath = $submission?->file_path;
        $originalName = $submission?->original_name;

        if ($request->hasFile('file')) {
            if ($submission?->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }
            $originalName = $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->store('assignments', 'public');
        }

        AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => $student->id],
            [
                'content' => $data['content'] ?? null,
                'file_path' => $filePath,
                'original_name' => $originalName,
                'submitted_at' => now(),
            ],
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan.');
    }

    public function download(Request $request, Assignment $assignment, AssignmentSubmission $submission)
    {
        $this->authorizeSchool($request, $assignment);

        abort_if($submission->assignment_id !== $assignment->id, 403);

        $user = $request->user();
        $isStaff = $user->isTeacher() || $user->isSuperAdmin() || $user->isSchoolAdmin();
        $isOwner = $user->isStudent() && $submission->student_id === $user->student?->id;

        abort_unless($isStaff || $isOwner, 403);
        abort_if(! $submission->file_path || ! Storage::disk('public')->exists($submission->file_path), 404);

        return Storage::disk('public')->download($submission->file_path, $submission->original_name);
    }

    protected function validateAssignment(Request $request): array
    {
        $schoolId = $this->schoolId($request);

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'class_id' => ['nullable', 'exists:classes,id,school_id,'.$schoolId],
            'subject_id' => ['nullable', 'exists:subjects,id,school_id,'.$schoolId],
            'due_at' => ['nullable', 'date'],
        ]);
    }

    protected function formOptions(int $schoolId): array
    {
        return [
            'classes' => SchoolClass::where('school_id', $schoolId)->orderBy('name')->get(),
            'subjects' => Subject::where('school_id', $schoolId)->orderBy('name')->get(),
        ];
    }

    protected function authorizeSchool(Request $request, Assignment $assignment): void
    {
        abort_if($assignment->school_id !== $this->schoolId($request), 403);
    }
}
