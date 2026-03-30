<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'content', 'date', 'location', 'mood',
    ];

    protected $casts = [
        'date' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Optional: mood color helper
    public function getMoodColorAttribute()
    {
        return match ($this->mood) {
            'happy' => 'yellow',
            'sad' => 'blue',
            'angry' => 'red',
            default => 'gray',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
