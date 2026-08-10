<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolClassController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $this->schoolId($request);

        $classes = SchoolClass::with(['major', 'homeroomTeacher'])
            ->where('school_id', $schoolId)
            ->withCount('students')
            ->orderBy('name')
            ->paginate(15);

        return view('classes.index', compact('classes'));
    }

    public function create(Request $request)
    {
        $schoolId = $this->schoolId($request);

        return view('classes.create', [
            'majors' => Major::where('school_id', $schoolId)->orderBy('name')->get(),
            'teachers' => Teacher::where('school_id', $schoolId)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $schoolId = $this->requireSchool($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('classes')->where(fn ($q) => $q->where('school_id', $schoolId))],
            'major_id' => ['nullable', 'exists:majors,id,school_id,' . $schoolId],
            'homeroom_teacher_id' => ['nullable', 'exists:teachers,id,school_id,' . $schoolId],
        ]);

        $data['school_id'] = $schoolId;

        SchoolClass::create($data);

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Request $request, SchoolClass $class)
    {
        $this->authorizeSchool($request, $class);

        $schoolId = $this->schoolId($request);

        return view('classes.edit', [
            'class' => $class,
            'majors' => Major::where('school_id', $schoolId)->orderBy('name')->get(),
            'teachers' => Teacher::where('school_id', $schoolId)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, SchoolClass $class)
    {
        $this->authorizeSchool($request, $class);

        $schoolId = $this->schoolId($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('classes')->where(fn ($q) => $q->where('school_id', $schoolId))->ignore($class->id)],
            'major_id' => ['nullable', 'exists:majors,id,school_id,' . $schoolId],
            'homeroom_teacher_id' => ['nullable', 'exists:teachers,id,school_id,' . $schoolId],
        ]);

        $class->update($data);

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Request $request, SchoolClass $class)
    {
        $this->authorizeSchool($request, $class);

        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil dihapus.');
    }

    protected function authorizeSchool(Request $request, SchoolClass $class): void
    {
        abort_if($class->school_id !== $this->schoolId($request), 403);
    }
}
