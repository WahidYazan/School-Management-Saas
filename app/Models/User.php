<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'school_id', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_SCHOOL_ADMIN = 'school_admin';
    public const ROLE_TEACHER = 'teacher';
    public const ROLE_STUDENT = 'student';
    public const ROLE_PARENT = 'parent';

    public const ROLES = [
        self::ROLE_SUPER_ADMIN,
        self::ROLE_SCHOOL_ADMIN,
        self::ROLE_TEACHER,
        self::ROLE_STUDENT,
        self::ROLE_PARENT,
    ];

    public const ROLE_LABELS = [
        self::ROLE_SUPER_ADMIN => 'Super Admin',
        self::ROLE_SCHOOL_ADMIN => 'Admin Sekolah',
        self::ROLE_TEACHER => 'Guru',
        self::ROLE_STUDENT => 'Siswa',
        self::ROLE_PARENT => 'Orang Tua',
    ];

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isSchoolAdmin(): bool
    {
        return $this->role === self::ROLE_SCHOOL_ADMIN;
    }

    public function isTeacher(): bool
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    public function isParent(): bool
    {
        return $this->role === self::ROLE_PARENT;
    }

    public function canManageSchoolData(): bool
    {
        return $this->isSuperAdmin() || $this->isSchoolAdmin();
    }

    public function roleLabel(): string
    {
        if ($this->role === self::ROLE_STUDENT && $this->student?->gender === 'P') {
            return 'Siswi';
        }

        return self::ROLE_LABELS[$this->role] ?? ($this->role ?? self::ROLE_STUDENT);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
