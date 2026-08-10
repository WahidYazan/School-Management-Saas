<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $this->schoolId($request);

        $students = Student::with(['class', 'major'])
            ->where('school_id', $schoolId)
            ->when($request->search, fn ($q, $search) => $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            }))
            ->when($request->class_id, fn ($q, $id) => $q->where('class_id', $id))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();

        return view('students.index', compact('students', 'classes'));
    }

    public function create(Request $request)
    {
        $schoolId = $this->schoolId($request);

        return view('students.create', [
            'classes' => SchoolClass::where('school_id', $schoolId)->orderBy('name')->get(),
            'majors' => Major::where('school_id', $schoolId)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $schoolId = $this->requireSchool($request);

        $data = $this->validateData($request, $schoolId);

        $data['school_id'] = $schoolId;
        $data['status'] = $request->input('status', 'active');
        $data['enrolled_at'] = $request->filled('enrolled_at') ? $request->input('enrolled_at') : now()->toDateString();

        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Request $request, Student $student)
    {
        $this->authorizeSchool($request, $student);

        $student->load(['class', 'major', 'attendance' => fn ($q) => $q->latest('date')->limit(30)]);

        return view('students.show', compact('student'));
    }

    public function edit(Request $request, Student $student)
    {
        $this->authorizeSchool($request, $student);

        $schoolId = $this->schoolId($request);

        return view('students.edit', [
            'student' => $student,
            'classes' => SchoolClass::where('school_id', $schoolId)->orderBy('name')->get(),
            'majors' => Major::where('school_id', $schoolId)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $this->authorizeSchool($request, $student);

        if ($request->user()->isTeacher()) {
            $data = $request->validate(['gender' => ['required', Rule::in(['L', 'P'])]]);

            $student->update(['gender' => $data['gender']]);

            return redirect()->route('students.show', $student)->with('success', 'Jenis kelamin siswa berhasil diperbarui.');
        }

        $data = $this->validateData($request, $this->schoolId($request), $student);
        $data['status'] = $request->input('status', 'active');

        $student->update($data);

        return redirect()->route('students.show', $student)->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Request $request, Student $student)
    {
        $this->authorizeSchool($request, $student);

        $student->delete();

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    protected function validateData(Request $request, int $schoolId, ?Student $student = null): array
    {
        $nisUnique = Rule::unique('students', 'nis')->where(fn ($q) => $q->where('school_id', $schoolId));
        if ($student) {
            $nisUnique = $nisUnique->ignore($student->id);
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['nullable', 'string', 'max:20', $nisUnique],
            'nisn' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', Rule::in(['L', 'P'])],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'class_id' => ['nullable', 'exists:classes,id,school_id,' . $schoolId],
            'major_id' => ['nullable', 'exists:majors,id,school_id,' . $schoolId],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_phone' => ['nullable', 'string', 'max:20'],
            'parent_email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', Rule::in(['active', 'alumni', 'mutasi'])],
            'enrolled_at' => ['nullable', 'date'],
        ]);
    }

    protected function authorizeSchool(Request $request, Student $student): void
    {
        abort_if($student->school_id !== $this->schoolId($request), 403);
    }
}
