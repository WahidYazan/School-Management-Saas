<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::withCount(['students', 'teachers', 'classes'])
            ->orderBy('name')
            ->get();

        return view('schools.index', compact('schools'));
    }

    public function create()
    {
        return view('schools.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        School::create($data);

        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function edit(School $school)
    {
        return view('schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $data = $this->validateData($request);

        $school->update($data);

        return redirect()->route('schools.index')->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function destroy(Request $request, School $school)
    {
        $school->delete();

        if (session('active_school_id') == $school->id) {
            session()->forget('active_school_id');
        }

        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'npsn' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);
    }
}
