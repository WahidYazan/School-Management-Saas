<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('assignments')]
class Assignment extends Model
{
    protected $fillable = [
        'school_id', 'class_id', 'subject_id', 'teacher_id',
        'title', 'description', 'due_at',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
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

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function isLate(): bool
    {
        return $this->due_at !== null && $this->due_at->isPast();
    }

    public function submissionFor(?Student $student): ?AssignmentSubmission
    {
        if (! $student) {
            return null;
        }

        return $this->submissions()->where('student_id', $student->id)->first();
    }
}
