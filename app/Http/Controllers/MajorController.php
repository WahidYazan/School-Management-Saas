<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MajorController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $this->schoolId($request);

        $majors = Major::withCount('students')
            ->where('school_id', $schoolId)
            ->orderBy('code')
            ->paginate(15);

        return view('majors.index', compact('majors'));
    }

    public function store(Request $request)
    {
        $schoolId = $this->requireSchool($request);

        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('majors')->where(fn ($q) => $q->where('school_id', $schoolId))],
            'name' => ['required', 'string', 'max:255'],
        ]);

        Major::create($data + ['school_id' => $schoolId]);

        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function destroy(Request $request, Major $major)
    {
        $this->authorizeSchool($request, $major);

        $major->delete();

        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil dihapus.');
    }

    protected function authorizeSchool(Request $request, Major $major): void
    {
        abort_if($major->school_id !== $this->schoolId($request), 403);
    }
}
