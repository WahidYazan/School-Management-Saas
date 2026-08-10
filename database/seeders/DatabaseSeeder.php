<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Major;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $password = Hash::make('password');

        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@productschool.test'],
            ['name' => 'Super Admin', 'password' => $password, 'role' => User::ROLE_SUPER_ADMIN, 'email_verified_at' => now()],
        );

        $school = School::firstOrCreate(
            ['name' => 'SMA Negeri 1 Nusantara'],
            ['npsn' => '20123456', 'address' => 'Jl. Pendidikan No. 1, Jakarta', 'phone' => '021-5551234', 'email' => 'info@sman1.test'],
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@productschool.test'],
            ['name' => 'Admin Sekolah', 'password' => $password, 'role' => User::ROLE_SCHOOL_ADMIN, 'school_id' => $school->id, 'email_verified_at' => now()],
        );

        $majors = collect([
            ['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak'],
            ['code' => 'TKJ', 'name' => 'Teknik Komputer dan Jaringan'],
            ['code' => 'DKV', 'name' => 'Desain Komunikasi Visual'],
        ])->map(fn ($m) => Major::firstOrCreate(['school_id' => $school->id, 'code' => $m['code']], $m + ['school_id' => $school->id]));

        $subjects = collect(['MTK', 'BIN', 'BING', 'IPA', 'RPL', 'JARKOM'])
            ->map(fn ($code) => Subject::firstOrCreate(
                ['school_id' => $school->id, 'code' => $code],
                ['school_id' => $school->id, 'code' => $code, 'name' => $this->subjectName($code)],
            ));

        $teacherData = [
            ['email' => 'guru1@productschool.test', 'nip' => '198001012005011001', 'name' => 'Budi Santoso', 'gender' => 'L', 'subjects' => ['MTK', 'IPA']],
            ['email' => 'guru2@productschool.test', 'nip' => '198502152010012002', 'name' => 'Siti Rahayu', 'gender' => 'P', 'subjects' => ['BIN', 'BING']],
            ['email' => 'guru3@productschool.test', 'nip' => '199003102015031003', 'name' => 'Agus Wijaya', 'gender' => 'L', 'subjects' => ['RPL', 'JARKOM']],
        ];

        $teachers = collect();
        foreach ($teacherData as $td) {
            $user = User::updateOrCreate(
                ['email' => $td['email']],
                ['name' => $td['name'], 'password' => $password, 'role' => User::ROLE_TEACHER, 'school_id' => $school->id, 'email_verified_at' => now()],
            );

            $teacher = Teacher::firstOrCreate(
                ['school_id' => $school->id, 'nip' => $td['nip']],
                ['user_id' => $user->id, 'name' => $td['name'], 'gender' => $td['gender'], 'phone' => '08' . rand(100000000, 999999999)],
            );

            $teacher->subjects()->sync(
                $subjects->whereIn('code', $td['subjects'])->pluck('id')
            );

            $teachers->push($teacher);
        }

        $classes = collect();
        $majorIds = $majors->pluck('id')->all();
        foreach (['X', 'XI', 'XII'] as $level) {
            foreach ($majors as $i => $major) {
                $num = $i + 1;
                $cls = SchoolClass::firstOrCreate(
                    ['school_id' => $school->id, 'name' => "{$level} {$major->code} {$num}"],
                    [
                        'school_id' => $school->id,
                        'major_id' => $major->id,
                        'homeroom_teacher_id' => $teachers->random()->id,
                    ],
                );
                $classes->push($cls);
            }
        }

        $firstNames = ['Ahmad', 'Bella', 'Citra', 'Dimas', 'Eka', 'Farhan', 'Gita', 'Hendra', 'Intan', 'Joko', 'Kirana', 'Lukman', 'Maya', 'Naufal', 'Oktavia', 'Putra', 'Qonita', 'Rizky', 'Sari', 'Taufik', 'Umar', 'Vina', 'Wahyu', 'Yulia', 'Zaki'];
        $lastNames = ['Pratama', 'Saputra', 'Lestari', 'Nugroho', 'Anggraini', 'Hidayat', 'Ramadhan', 'Kusuma', 'Wulandari', 'Firmansyah'];

        $maleNames = ['Ahmad', 'Dimas', 'Farhan', 'Hendra', 'Joko', 'Lukman', 'Naufal', 'Putra', 'Rizky', 'Taufik', 'Umar', 'Wahyu', 'Zaki'];
        $femaleNames = ['Bella', 'Citra', 'Eka', 'Gita', 'Intan', 'Kirana', 'Maya', 'Oktavia', 'Qonita', 'Sari', 'Vina', 'Yulia'];

        $studentUsers = [];
        $nisCounter = 1000;
        $now = now();

        foreach ($classes as $class) {
            $count = rand(8, 12);
            for ($i = 0; $i < $count; $i++) {
                $nisCounter++;
                $gender = rand(0, 1) ? 'L' : 'P';
                $first = $gender === 'L' ? $maleNames[array_rand($maleNames)] : $femaleNames[array_rand($femaleNames)];
                $last = $lastNames[array_rand($lastNames)];
                $name = "$first $last";
                $nisn = (string) rand(1000000000, 9999999999);
                $email = strtolower(str_replace(' ', '.', $name)) . "@siswa.test";

                $studentUser = User::updateOrCreate(
                    ['email' => $email],
                    ['name' => $name, 'password' => $password, 'role' => User::ROLE_STUDENT, 'school_id' => $school->id, 'email_verified_at' => now()],
                );
                $studentUsers[] = $studentUser;

                Student::firstOrCreate(
                    ['school_id' => $school->id, 'nis' => (string) $nisCounter],
                    [
                        'user_id' => $studentUser->id,
                        'class_id' => $class->id,
                        'major_id' => $class->major_id,
                        'nisn' => $nisn,
                        'name' => $name,
                        'gender' => $gender,
                        'birth_place' => 'Jakarta',
                        'birth_date' => $now->copy()->subYears(rand(15, 18))->subDays(rand(1, 350))->toDateString(),
                        'address' => "Jl. Contoh No. {$nisCounter}, Jakarta",
                        'phone' => '08' . rand(100000000, 999999999),
                        'parent_name' => "Bpk/Ibu $last",
                        'parent_phone' => '08' . rand(100000000, 999999999),
                        'status' => 'active',
                        'enrolled_at' => $now->copy()->startOfYear()->toDateString(),
                    ],
                );
            }
        }

        $this->seedAttendance($school, $classes);

        $this->seedCustomAccounts($school);

        Announcement::updateOrCreate(
            ['school_id' => $school->id, 'title' => 'Selamat Datang di Tahun Ajaran Baru'],
            [
                'author_id' => $admin->id,
                'body' => 'Seluruh siswa diharapkan mempersiapkan diri untuk memulai kegiatan belajar mengajar. Jadwal lengkap dapat dilihat di masing-masing wali kelas.',
                'audience' => ['all'],
            ],
        );

        $this->command->info('Seeder selesai.');
        $this->command->info("Login demo:");
        $this->command->info("  Super Admin : superadmin@productschool.test / password");
        $this->command->info("  Admin Sekolah: admin@productschool.test / password");
        $this->command->info("  Guru        : guru1@productschool.test / password");
        $this->command->info("  Siswa       : gunakan email siswa (lihat database) / password");
    }

    protected function subjectName(string $code): string
    {
        return match ($code) {
            'MTK' => 'Matematika',
            'BIN' => 'Bahasa Indonesia',
            'BING' => 'Bahasa Inggris',
            'IPA' => 'Ilmu Pengetahuan Alam',
            'RPL' => 'Pemrograman Web',
            'JARKOM' => 'Jaringan Komputer',
            default => $code,
        };
    }

    protected function seedCustomAccounts(School $school): void
    {
        User::updateOrCreate(
            ['email' => 'supermi@minuman.test'],
            ['name' => 'Supermi', 'password' => Hash::make('supermi'), 'role' => User::ROLE_SUPER_ADMIN, 'email_verified_at' => now()],
        );

        $guruUser = User::updateOrCreate(
            ['email' => 'gurumi@minuman.test'],
            ['name' => 'Gurumi', 'password' => Hash::make('gurumi'), 'role' => User::ROLE_TEACHER, 'school_id' => $school->id, 'email_verified_at' => now()],
        );

        Teacher::updateOrCreate(
            ['school_id' => $school->id, 'nip' => '198812120000000001'],
            ['user_id' => $guruUser->id, 'name' => 'Gurumi', 'gender' => 'P', 'phone' => '081234567890'],
        );

        $siswaUser = User::updateOrCreate(
            ['email' => 'siswami@minuman.test'],
            ['name' => 'Siswami', 'password' => Hash::make('siswami'), 'role' => User::ROLE_STUDENT, 'school_id' => $school->id, 'email_verified_at' => now()],
        );

        $class = SchoolClass::where('school_id', $school->id)->first();
        $maxNis = (int) Student::where('school_id', $school->id)->max('nis');

        Student::updateOrCreate(
            ['school_id' => $school->id, 'nis' => (string) ($maxNis + 1)],
            [
                'user_id' => $siswaUser->id,
                'class_id' => $class->id,
                'major_id' => $class->major_id,
                'name' => 'Siswami',
                'gender' => 'P',
                'status' => 'active',
                'enrolled_at' => now()->toDateString(),
            ],
        );
    }

    protected function seedAttendance(School $school, $classes): void
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        foreach ($classes as $class) {
            foreach ($class->students as $student) {
                foreach ([$yesterday, $today] as $date) {
                    $roll = rand(1, 100);
                    $status = $roll <= 78 ? Attendance::STATUS_HADIR
                        : ($roll <= 86 ? Attendance::STATUS_SAKIT
                        : ($roll <= 93 ? Attendance::STATUS_IZIN
                        : ($roll <= 98 ? Attendance::STATUS_TERLAMBAT : Attendance::STATUS_ALPA)));

                    Attendance::firstOrCreate(
                        ['school_id' => $school->id, 'student_id' => $student->id, 'date' => $date],
                        [
                            'class_id' => $class->id,
                            'teacher_id' => $class->homeroom_teacher_id,
                            'status' => $status,
                            'note' => $status === Attendance::STATUS_HADIR ? null : 'Keterangan dicatat oleh wali kelas.',
                        ],
                    );
                }
            }
        }
    }
}
