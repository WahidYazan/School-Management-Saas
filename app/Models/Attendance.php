<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('attendance')]
class Attendance extends Model
{
    public const STATUS_HADIR = 'hadir';
    public const STATUS_SAKIT = 'sakit';
    public const STATUS_IZIN = 'izin';
    public const STATUS_ALPA = 'alpa';
    public const STATUS_TERLAMBAT = 'terlambat';

    public const STATUSES = [
        self::STATUS_HADIR,
        self::STATUS_SAKIT,
        self::STATUS_IZIN,
        self::STATUS_ALPA,
        self::STATUS_TERLAMBAT,
    ];

    public const STATUS_LABELS = [
        self::STATUS_HADIR => 'Hadir',
        self::STATUS_SAKIT => 'Sakit',
        self::STATUS_IZIN => 'Izin',
        self::STATUS_ALPA => 'Alpa',
        self::STATUS_TERLAMBAT => 'Terlambat',
    ];

    protected $fillable = ['school_id', 'class_id', 'student_id', 'teacher_id', 'date', 'status', 'note'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
