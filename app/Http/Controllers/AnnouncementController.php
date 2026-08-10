<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $this->schoolId($request);

        $announcements = Announcement::with('author')
            ->where('school_id', $schoolId)
            ->latest()
            ->paginate(15);

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $schoolId = $this->requireSchool($request);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'audience' => ['nullable', 'array'],
            'audience.*' => [Rule::in([
                Announcement::AUDIENCE_ALL,
                Announcement::AUDIENCE_TEACHERS,
                Announcement::AUDIENCE_STUDENTS,
                Announcement::AUDIENCE_PARENTS,
            ])],
        ]);

        $audience = $data['audience'] ?? [Announcement::AUDIENCE_ALL];

        Announcement::create([
            'school_id' => $schoolId,
            'author_id' => $request->user()->id,
            'title' => $data['title'],
            'body' => $data['body'],
            'audience' => $audience,
        ]);

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    public function destroy(Request $request, Announcement $announcement)
    {
        abort_if($announcement->school_id !== $this->schoolId($request), 403);

        $announcement->delete();

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }

    protected function authorizeSchool(Request $request, Announcement $announcement): void
    {
        abort_if($announcement->school_id !== $this->schoolId($request), 403);
    }
}
