<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a user who is watching a ticket for notifications.
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 */
class TicketWatcher extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['ticket_id', 'user_id'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}