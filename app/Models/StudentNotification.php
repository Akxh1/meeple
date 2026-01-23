<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'sender_id',
        'type',
        'title',
        'message',
        'is_read',
        'email_sent',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'email_sent' => 'boolean',
    ];

    /**
     * Get the student that owns the notification
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the sender (instructor/admin) who sent the notification
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get type icon
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'warning' => '⚠️',
            'at_risk' => '🚨',
            'success' => '✅',
            default => 'ℹ️',
        };
    }

    /**
     * Get type color class
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'warning' => 'text-amber-600 dark:text-amber-400',
            'at_risk' => 'text-red-600 dark:text-red-400',
            'success' => 'text-green-600 dark:text-green-400',
            default => 'text-indigo-600 dark:text-indigo-400',
        };
    }
}
