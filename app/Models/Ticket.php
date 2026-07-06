<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Represents an IT support ticket.
 *
 * @property int         $id
 * @property string      $ticket_number
 * @property string      $title
 * @property string      $description
 * @property string      $priority
 * @property string      $status
 * @property int         $user_id
 * @property int|null    $assigned_to
 * @property int|null    $category_id
 * @property string|null $due_date
 * @property string|null $resolution_notes
 * @property string|null $resolved_at
 */
class Ticket extends Model
{
    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'priority',
        'status',
        'user_id',
        'assigned_to',
        'category_id',
        'due_date',
        'resolution_notes',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date'    => 'date',
            'resolved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function watchers(): HasMany
    {
        return $this->hasMany(TicketWatcher::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(TicketHistory::class);
    }

    public function isResolved(): bool
    {
        return $this->status === 'Closed' && $this->resolved_at !== null;
    }

    public function isWatchedBy(User $user): bool
    {
        return $this->watchers()->where('user_id', $user->id)->exists();
    }
}