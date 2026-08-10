<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('students')]
class Student extends Model
{
    protected $fillable = [
        'school_id', 'user_id', 'class_id', 'major_id',
        'nis', 'nisn', 'name', 'gender', 'birth_place', 'birth_date',
        'address', 'phone', 'parent_name', 'parent_phone', 'parent_email',
        'status', 'enrolled_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'enrolled_at' => 'date',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
