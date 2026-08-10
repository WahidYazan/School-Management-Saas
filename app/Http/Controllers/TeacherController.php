<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $this->schoolId($request);

        $teachers = Teacher::with(['subjects', 'homeroomClasses'])
            ->where('school_id', $schoolId)
            ->when($request->search, fn ($q, $search) => $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('teachers.index', compact('teachers'));
    }

    public function create(Request $request)
    {
        $schoolId = $this->schoolId($request);

        return view('teachers.create', [
            'subjects' => Subject::where('school_id', $schoolId)->orderBy('name')->get(),
            'classes' => SchoolClass::where('school_id', $schoolId)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $schoolId = $this->requireSchool($request);

        $data = $this->validateData($request, $schoolId);

        $teacher = Teacher::create([
            'school_id' => $schoolId,
            'nip' => $data['nip'] ?? null,
            'name' => $data['name'],
            'gender' => $data['gender'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        $teacher->subjects()->sync($data['subject_ids'] ?? []);
        $this->syncHomeroomClasses($teacher, $data['homeroom_class_ids'] ?? []);

        if (! empty($data['email'])) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password'] ?? 'password'),
                    'role' => User::ROLE_TEACHER,
                    'school_id' => $schoolId,
                ],
            );
            $teacher->update(['user_id' => $user->id]);
        }

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function show(Request $request, Teacher $teacher)
    {
        $this->authorizeSchool($request, $teacher);

        $teacher->load(['subjects', 'homeroomClasses']);

        return view('teachers.show', compact('teacher'));
    }

    public function edit(Request $request, Teacher $teacher)
    {
        $this->authorizeSchool($request, $teacher);

        $schoolId = $this->schoolId($request);

        return view('teachers.edit', [
            'teacher' => $teacher,
            'subjects' => Subject::where('school_id', $schoolId)->orderBy('name')->get(),
            'classes' => SchoolClass::where('school_id', $schoolId)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $this->authorizeSchool($request, $teacher);

        $data = $this->validateData($request, $this->schoolId($request), $teacher);

        $teacher->update([
            'nip' => $data['nip'] ?? null,
            'name' => $data['name'],
            'gender' => $data['gender'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        $teacher->subjects()->sync($data['subject_ids'] ?? []);
        $this->syncHomeroomClasses($teacher, $data['homeroom_class_ids'] ?? []);

        return redirect()->route('teachers.show', $teacher)->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Request $request, Teacher $teacher)
    {
        $this->authorizeSchool($request, $teacher);

        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil dihapus.');
    }

    protected function validateData(Request $request, int $schoolId, ?Teacher $teacher = null): array
    {
        $nipUnique = Rule::unique('teachers', 'nip')->where(fn ($q) => $q->where('school_id', $schoolId));
        if ($teacher) {
            $nipUnique = $nipUnique->ignore($teacher->id);
        }

        $emailUnique = Rule::unique('users', 'email');
        if ($teacher?->user_id) {
            $emailUnique = $emailUnique->ignore($teacher->user_id);
        }

        return $request->validate([
            'nip' => ['nullable', 'string', 'max:30', $nipUnique],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['L', 'P'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255', $emailUnique],
            'subject_ids' => ['nullable', 'array'],
            'subject_ids.*' => ['exists:subjects,id,school_id,' . $schoolId],
            'homeroom_class_ids' => ['nullable', 'array'],
            'homeroom_class_ids.*' => ['exists:classes,id,school_id,' . $schoolId],
        ]);
    }

    protected function syncHomeroomClasses(Teacher $teacher, array $classIds): void
    {
        SchoolClass::where('school_id', $teacher->school_id)
            ->where('homeroom_teacher_id', $teacher->id)
            ->whereNotIn('id', $classIds)
            ->update(['homeroom_teacher_id' => null]);

        if (! empty($classIds)) {
            SchoolClass::where('school_id', $teacher->school_id)
                ->whereIn('id', $classIds)
                ->update(['homeroom_teacher_id' => $teacher->id]);
        }
    }

    protected function authorizeSchool(Request $request, Teacher $teacher): void
    {
        abort_if($teacher->school_id !== $this->schoolId($request), 403);
    }
}
