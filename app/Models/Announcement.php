<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('announcements')]
class Announcement extends Model
{
    public const AUDIENCE_ALL = 'all';
    public const AUDIENCE_TEACHERS = 'teachers';
    public const AUDIENCE_STUDENTS = 'students';
    public const AUDIENCE_PARENTS = 'parents';

    protected $fillable = ['school_id', 'author_id', 'title', 'body', 'audience'];

    protected function casts(): array
    {
        return [
            'audience' => 'array',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
