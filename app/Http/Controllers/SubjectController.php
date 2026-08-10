<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $this->schoolId($request);

        $subjects = Subject::withCount('teachers')
            ->where('school_id', $schoolId)
            ->orderBy('code')
            ->paginate(15);

        return view('subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $schoolId = $this->requireSchool($request);

        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('subjects')->where(fn ($q) => $q->where('school_id', $schoolId))],
            'name' => ['required', 'string', 'max:255'],
        ]);

        Subject::create($data + ['school_id' => $schoolId]);

        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function destroy(Request $request, Subject $subject)
    {
        $this->authorizeSchool($request, $subject);

        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    protected function authorizeSchool(Request $request, Subject $subject): void
    {
        abort_if($subject->school_id !== $this->schoolId($request), 403);
    }
}
