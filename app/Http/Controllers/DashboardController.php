<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isSuperAdmin() && ! $this->schoolId($request)) {
            return $this->superAdminOverview();
        }

        $schoolId = $this->schoolId($request);

        $studentCount = Student::where('school_id', $schoolId)->where('status', 'active')->count();
        $teacherCount = Teacher::where('school_id', $schoolId)->count();
        $classCount = SchoolClass::where('school_id', $schoolId)->count();
        $subjectCount = Subject::where('school_id', $schoolId)->count();

        $today = now()->toDateString();

        $attendanceSummary = array_fill_keys(Attendance::STATUSES, 0);
        foreach (Attendance::where('school_id', $schoolId)
            ->whereDate('date', $today)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get() as $row) {
            $attendanceSummary[$row->status] = (int) $row->total;
        }
        $attendanceCount = array_sum($attendanceSummary);

        $recentAnnouncements = Announcement::where('school_id', $schoolId)
            ->latest()
            ->limit(5)
            ->get();

        $student = $user->isStudent() ? $user->student : null;

        $studentClassDistribution = [];
        if ($user->isTeacher() || $user->isSuperAdmin()) {
            $studentClassDistribution = Student::where('school_id', $schoolId)
                ->where('status', 'active')
                ->with('class:id,name')
                ->get()
                ->groupBy(fn ($s) => $s->class?->name ?? 'Tanpa Kelas')
                ->map(fn ($g) => $g->count())
                ->sortDesc();
        }

        $classAttendanceSummary = [];
        if ($student && $student->class_id) {
            $classAttendanceSummary = array_fill_keys(Attendance::STATUSES, 0);
            foreach (Attendance::where('school_id', $schoolId)
                ->whereDate('date', $today)
                ->whereIn('student_id', Student::where('class_id', $student->class_id)->select('id'))
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->get() as $row) {
                $classAttendanceSummary[$row->status] = (int) $row->total;
            }
        }

        return view('dashboard', compact(
            'studentCount', 'teacherCount', 'classCount', 'subjectCount',
            'attendanceCount', 'attendanceSummary', 'recentAnnouncements',
            'studentClassDistribution', 'classAttendanceSummary',
        ));
    }

    protected function superAdminOverview()
    {
        $schools = School::withCount(['students', 'teachers', 'classes'])
            ->orderBy('name')
            ->get();

        return view('dashboard', compact('schools'));
    }
}
