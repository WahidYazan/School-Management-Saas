<?php

namespace App\Http\Controllers;

use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $this->schoolId($request);

        $query = AssignmentSubmission::whereHas('assignment', fn ($q) => $q->where('school_id', $schoolId));

        if ($search = $request->search) {
            $query->whereHas('student', fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('nis', 'like', "%{$search}%"));
        }

        $students = $query
            ->selectRaw('student_id, count(*) as total, max(submitted_at) as last_submitted_at')
            ->groupBy('student_id')
            ->orderByDesc('total')
            ->with('student.class')
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'students' => AssignmentSubmission::whereHas('assignment', fn ($q) => $q->where('school_id', $schoolId))
                ->distinct()
                ->count('student_id'),
            'submissions' => AssignmentSubmission::whereHas('assignment', fn ($q) => $q->where('school_id', $schoolId))->count(),
        ];

        return view('submissions.index', compact('students', 'summary'));
    }
}
