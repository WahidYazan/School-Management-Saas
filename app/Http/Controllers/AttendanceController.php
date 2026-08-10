<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $this->schoolId($request);

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();

        $attendances = Attendance::with(['class', 'student'])
            ->where('school_id', $schoolId)
            ->when($request->date, fn ($q, $date) => $q->whereDate('date', $date), fn ($q) => $q->whereDate('date', now()->toDateString()))
            ->when($request->class_id, fn ($q, $id) => $q->where('class_id', $id))
            ->orderBy('date', 'desc')
            ->orderBy('student_id')
            ->paginate(30)
            ->withQueryString();

        return view('attendance.index', compact('attendances', 'classes'));
    }

    public function create(Request $request)
    {
        $schoolId = $this->schoolId($request);

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();

        $classId = $request->query('class_id', $classes->first()?->id);
        $date = $request->query('date', now()->toDateString());

        $students = collect();
        $existing = collect();

        if ($classId) {
            $students = Student::with('class')
                ->where('school_id', $schoolId)
                ->where('class_id', $classId)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            $existing = Attendance::where('school_id', $schoolId)
                ->where('class_id', $classId)
                ->whereDate('date', $date)
                ->get()
                ->keyBy('student_id');
        }

        return view('attendance.create', compact('classes', 'classId', 'date', 'students', 'existing'));
    }

    public function store(Request $request)
    {
        $schoolId = $this->requireSchool($request);

        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id,school_id,' . $schoolId],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'statuses' => ['required', 'array'],
            'statuses.*' => [Rule::in(Attendance::STATUSES)],
            'notes' => ['nullable', 'array'],
            'notes.*' => ['nullable', 'string', 'max:255'],
        ]);

        $class = SchoolClass::findOrFail($data['class_id']);
        abort_if($class->school_id !== $schoolId, 403);

        $validStudentIds = $class->students()
            ->where('school_id', $schoolId)
            ->pluck('id')
            ->flip();

        $statuses = array_intersect_key($data['statuses'], $validStudentIds->all());

        if (empty($statuses)) {
            return redirect()->back()->with('error', 'Tidak ada siswa valid yang dikirim.');
        }

        $teacher = Teacher::where('school_id', $schoolId)
            ->where('user_id', $request->user()->id)
            ->first();

        foreach ($statuses as $studentId => $status) {
            $attendance = Attendance::where('school_id', $schoolId)
                ->where('student_id', $studentId)
                ->whereDate('date', $data['date'])
                ->first();

            if ($attendance) {
                $attendance->update([
                    'class_id' => $data['class_id'],
                    'teacher_id' => $teacher?->id ?? $class->homeroom_teacher_id,
                    'status' => $status,
                    'note' => $data['notes'][$studentId] ?? null,
                ]);
            } else {
                Attendance::create([
                    'school_id' => $schoolId,
                    'class_id' => $data['class_id'],
                    'student_id' => $studentId,
                    'teacher_id' => $teacher?->id ?? $class->homeroom_teacher_id,
                    'date' => $data['date'],
                    'status' => $status,
                    'note' => $data['notes'][$studentId] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('attendance.index', ['date' => $data['date'], 'class_id' => $data['class_id']])
            ->with('success', 'Absensi berhasil disimpan.');
    }

    public function show(Request $request, SchoolClass $class, string $date)
    {
        $schoolId = $this->schoolId($request);

        abort_if($class->school_id !== $schoolId, 403);

        $attendances = Attendance::with(['student'])
            ->where('school_id', $schoolId)
            ->where('class_id', $class->id)
            ->whereDate('date', $date)
            ->get();

        $summary = [];
        foreach (Attendance::STATUSES as $status) {
            $summary[$status] = $attendances->where('status', $status)->count();
        }

        return view('attendance.show', compact('class', 'date', 'attendances', 'summary'));
    }
}
