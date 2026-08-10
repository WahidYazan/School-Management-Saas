<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function seedData(): void
    {
        $this->seed();
    }

    protected function user(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    public function test_dashboard_shows_stats_for_authenticated_user(): void
    {
        $this->seedData();

        $response = $this->actingAs($this->user('admin@productschool.test'))->get('/');

        $response->assertOk();
        $response->assertSee('Jumlah Siswa');
        $response->assertSee('Jumlah Guru');
    }

    public function test_guest_redirected_from_dashboard(): void
    {
        $this->seedData();

        $this->get('/')->assertRedirect('/login');
    }

    public function test_teacher_cannot_manage_teachers(): void
    {
        $this->seedData();

        $this->actingAs($this->user('guru1@productschool.test'))
            ->get('/teachers/create')
            ->assertForbidden();
    }

    public function test_teacher_can_view_students_and_take_attendance(): void
    {
        $this->seedData();

        $teacher = $this->user('guru1@productschool.test');
        $schoolId = $teacher->school_id;

        $this->actingAs($teacher)->get('/students')->assertOk();
        $this->actingAs($teacher)->get('/attendance/create')->assertOk();

        $student = Student::where('school_id', $schoolId)->first();

        $response = $this->actingAs($teacher)->post('/attendance', [
            'class_id' => $student->class_id,
            'date' => now()->toDateString(),
            'statuses' => [$student->id => Attendance::STATUS_SAKIT],
            'notes' => [$student->id => 'Demam'],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('attendance', [
            'student_id' => $student->id,
            'status' => Attendance::STATUS_SAKIT,
            'note' => 'Demam',
        ]);
    }

    public function test_admin_can_open_student_create_form(): void
    {
        $this->seedData();

        $this->actingAs($this->user('admin@productschool.test'))
            ->get('/students/create')
            ->assertOk()
            ->assertSee('Tambah Siswa');
    }

    public function test_admin_can_create_student(): void
    {
        $this->seedData();

        $admin = $this->user('admin@productschool.test');
        $school = School::find($admin->school_id);
        $class = $school->classes()->first();

        $response = $this->actingAs($admin)->post('/students', [
            'name' => 'Siswa Baru Test',
            'nis' => '999999',
            'gender' => 'L',
            'class_id' => $class->id,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('students.index'));

        $this->assertDatabaseHas('students', [
            'school_id' => $admin->school_id,
            'name' => 'Siswa Baru Test',
            'nis' => '999999',
        ]);
    }

    public function test_admin_cannot_access_student_of_other_school(): void
    {
        $this->seedData();

        $school2 = School::create(['name' => 'Sekolah Lain']);

        $foreignStudent = Student::create([
            'school_id' => $school2->id,
            'name' => 'Siswa Asing',
            'status' => 'active',
        ]);

        $admin = $this->user('admin@productschool.test');

        $this->actingAs($admin)
            ->get("/students/{$foreignStudent->id}")
            ->assertForbidden();

        $this->actingAs($admin)
            ->delete("/students/{$foreignStudent->id}")
            ->assertForbidden();
    }

    public function test_super_admin_can_access_admin_areas(): void
    {
        $this->seedData();

        $this->actingAs($this->user('superadmin@productschool.test'))
            ->get('/teachers')
            ->assertOk();

        $this->actingAs($this->user('superadmin@productschool.test'))
            ->get('/classes')
            ->assertOk();
    }

    public function test_teacher_marker_is_recorded_when_matching_user_exists(): void
    {
        $this->seedData();

        $teacherUser = $this->user('guru1@productschool.test');
        $teacher = Teacher::where('user_id', $teacherUser->id)->first();
        $student = Student::where('school_id', $teacherUser->school_id)->first();

        $this->actingAs($teacherUser)->post('/attendance', [
            'class_id' => $student->class_id,
            'date' => now()->toDateString(),
            'statuses' => [$student->id => Attendance::STATUS_HADIR],
            'notes' => [$student->id => null],
        ]);

        $this->assertDatabaseHas('attendance', [
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
        ]);
    }

    public function test_super_admin_without_school_cannot_create_major(): void
    {
        $this->seedData();

        $this->actingAs($this->user('superadmin@productschool.test'))
            ->post('/majors', ['code' => 'X1', 'name' => 'Uji'])
            ->assertForbidden();

        $this->assertDatabaseMissing('majors', ['code' => 'X1']);
    }

    public function test_search_does_not_leak_students_from_other_school(): void
    {
        $this->seedData();

        $otherSchool = School::create(['name' => 'Sekolah Lain']);
        Student::create([
            'school_id' => $otherSchool->id,
            'name' => 'Siswa Lain Sekolah Unik',
            'nis' => '777777',
            'status' => 'active',
        ]);

        $admin = $this->user('admin@productschool.test');

        $response = $this->actingAs($admin)->get('/students?search=Siswa Lain Sekolah Unik');

        $response->assertOk();
        $response->assertSee('Tidak ada data siswa');
    }

    public function test_attendance_store_ignores_student_from_other_school(): void
    {
        $this->seedData();

        $admin = $this->user('admin@productschool.test');
        $schoolId = $admin->school_id;

        $student = Student::where('school_id', $schoolId)->first();

        $otherSchool = School::create(['name' => 'Sekolah Lain']);
        $foreignStudent = Student::create([
            'school_id' => $otherSchool->id,
            'name' => 'Siswa Asing Absensi',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->post('/attendance', [
            'class_id' => $student->class_id,
            'date' => now()->toDateString(),
            'statuses' => [
                $student->id => Attendance::STATUS_HADIR,
                $foreignStudent->id => Attendance::STATUS_HADIR,
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('attendance', ['student_id' => $student->id]);
        $this->assertDatabaseMissing('attendance', ['student_id' => $foreignStudent->id]);
    }

    public function test_attendance_show_is_forbidden_for_other_school_class(): void
    {
        $this->seedData();

        $admin = $this->user('admin@productschool.test');

        $otherSchool = School::create(['name' => 'Sekolah Lain']);
        $otherClass = SchoolClass::create([
            'school_id' => $otherSchool->id,
            'name' => 'Kelas Lain',
        ]);

        $this->actingAs($admin)
            ->get("/attendance/{$otherClass->id}/" . now()->toDateString())
            ->assertForbidden();
    }

    public function test_super_admin_can_switch_school_and_manage_its_data(): void
    {
        $this->seedData();

        $superAdmin = $this->user('superadmin@productschool.test');

        $this->actingAs($superAdmin)->get('/')
            ->assertOk()
            ->assertSee('Semua Sekolah');

        $school = School::where('name', 'SMA Negeri 1 Nusantara')->firstOrFail();

        $this->actingAs($superAdmin)->post('/superadmin/switch-school', ['school_id' => $school->id])
            ->assertRedirect(route('dashboard'));

        $this->actingAs($superAdmin)->get('/students')
            ->assertOk()
            ->assertSee('SMA Negeri 1 Nusantara');

        $this->actingAs($superAdmin)->get('/')
            ->assertOk()
            ->assertSee('Jumlah Siswa');

        $this->actingAs($superAdmin)->post('/majors', ['code' => 'X99', 'name' => 'Uji Super'])
            ->assertRedirect(route('majors.index'));

        $this->assertDatabaseHas('majors', ['school_id' => $school->id, 'code' => 'X99']);
    }

    public function test_super_admin_can_open_school_create_form(): void
    {
        $this->seedData();

        $this->actingAs($this->user('superadmin@productschool.test'))
            ->get('/schools/create')
            ->assertOk()
            ->assertSee('Tambah Sekolah');
    }

    public function test_super_admin_can_create_and_update_school(): void
    {
        $this->seedData();

        $superAdmin = $this->user('superadmin@productschool.test');

        $this->actingAs($superAdmin)->post('/schools', [
            'name' => 'SMP Negeri 9 Jakarta',
            'npsn' => '20111111',
            'email' => 'info@smp9.test',
        ])->assertRedirect(route('schools.index'));

        $school = School::where('name', 'SMP Negeri 9 Jakarta')->firstOrFail();
        $this->assertSame('20111111', $school->npsn);

        $this->actingAs($superAdmin)->put("/schools/{$school->id}", [
            'name' => 'SMP Negeri 9 Jakarta Baru',
            'npsn' => '20111111',
        ])->assertRedirect(route('schools.index'));

        $this->assertDatabaseHas('schools', ['id' => $school->id, 'name' => 'SMP Negeri 9 Jakarta Baru']);
    }

    public function test_school_admin_cannot_manage_schools(): void
    {
        $this->seedData();

        $this->actingAs($this->user('admin@productschool.test'))
            ->get('/schools')
            ->assertForbidden();

        $this->actingAs($this->user('admin@productschool.test'))
            ->post('/schools', ['name' => 'Hacked School'])
            ->assertForbidden();
    }

    public function test_admin_can_create_teacher_with_homeroom_classes(): void
    {
        $this->seedData();

        $admin = $this->user('admin@productschool.test');
        $schoolId = $admin->school_id;

        $class = SchoolClass::where('school_id', $schoolId)->first();
        $subject = \App\Models\Subject::where('school_id', $schoolId)->first();

        $this->actingAs($admin)->post('/teachers', [
            'name' => 'Guru Baru Homeroom',
            'gender' => 'L',
            'subject_ids' => [$subject->id],
            'homeroom_class_ids' => [$class->id],
        ])->assertRedirect(route('teachers.index'));

        $teacher = Teacher::where('name', 'Guru Baru Homeroom')->firstOrFail();

        $this->assertSame($teacher->id, $class->fresh()->homeroom_teacher_id);
        $this->assertDatabaseHas('teacher_subject', [
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
        ]);
    }

    public function test_teacher_cannot_be_assigned_class_from_other_school(): void
    {
        $this->seedData();

        $admin = $this->user('admin@productschool.test');

        $otherSchool = School::create(['name' => 'Sekolah Lain']);
        $otherClass = SchoolClass::create(['school_id' => $otherSchool->id, 'name' => 'Kelas Lain']);
        $otherSubject = \App\Models\Subject::create([
            'school_id' => $otherSchool->id,
            'code' => 'ASING',
            'name' => 'Mapel Asing',
        ]);

        $this->actingAs($admin)->post('/teachers', [
            'name' => 'Guru Bocor',
            'gender' => 'P',
            'subject_ids' => [$otherSubject->id],
            'homeroom_class_ids' => [$otherClass->id],
        ])->assertSessionHasErrors(['subject_ids.0', 'homeroom_class_ids.0']);
    }

    public function test_student_cannot_be_assigned_class_from_other_school(): void
    {
        $this->seedData();

        $admin = $this->user('admin@productschool.test');

        $otherSchool = School::create(['name' => 'Sekolah Lain']);
        $otherClass = SchoolClass::create(['school_id' => $otherSchool->id, 'name' => 'Kelas Lain']);

        $this->actingAs($admin)->post('/students', [
            'name' => 'Siswa Bocor',
            'gender' => 'L',
            'class_id' => $otherClass->id,
            'status' => 'active',
        ])->assertSessionHasErrors('class_id');
    }

    public function test_class_cannot_reference_teacher_from_other_school(): void
    {
        $this->seedData();

        $admin = $this->user('admin@productschool.test');

        $otherSchool = School::create(['name' => 'Sekolah Lain']);
        $otherTeacher = Teacher::create([
            'school_id' => $otherSchool->id,
            'name' => 'Guru Asing',
            'gender' => 'L',
        ]);

        $this->actingAs($admin)->post('/classes', [
            'name' => 'X Asing',
            'homeroom_teacher_id' => $otherTeacher->id,
        ])->assertSessionHasErrors('homeroom_teacher_id');
    }

    public function test_homeroom_assignment_moves_between_teachers(): void
    {
        $this->seedData();

        $admin = $this->user('admin@productschool.test');
        $schoolId = $admin->school_id;

        $teacherA = Teacher::where('school_id', $schoolId)->first();
        $class = SchoolClass::where('school_id', $schoolId)->first();

        $class->update(['homeroom_teacher_id' => $teacherA->id]);

        $this->actingAs($admin)->post('/teachers', [
            'name' => 'Guru Pengganti',
            'gender' => 'P',
            'homeroom_class_ids' => [$class->id],
        ])->assertRedirect(route('teachers.index'));

        $teacherB = Teacher::where('name', 'Guru Pengganti')->firstOrFail();

        $this->assertSame($teacherB->id, $class->fresh()->homeroom_teacher_id);
        $this->assertDatabaseMissing('classes', [
            'id' => $class->id,
            'homeroom_teacher_id' => $teacherA->id,
        ]);
    }

    public function test_female_student_role_label_is_siswi(): void
    {
        $this->seedData();

        $female = Student::where('gender', 'P')->whereNotNull('user_id')->firstOrFail();
        $this->assertSame('Siswi', $female->user->roleLabel());

        $male = Student::where('gender', 'L')->whereNotNull('user_id')->firstOrFail();
        $this->assertSame('Siswa', $male->user->roleLabel());
    }

    public function test_super_admin_can_delete_school_with_data(): void
    {
        $this->seedData();

        $superAdmin = $this->user('superadmin@productschool.test');
        $school = School::where('name', 'SMA Negeri 1 Nusantara')->firstOrFail();

        $this->actingAs($superAdmin)
            ->delete("/schools/{$school->id}")
            ->assertRedirect(route('schools.index'));

        $this->assertDatabaseMissing('schools', ['id' => $school->id]);
    }
}
