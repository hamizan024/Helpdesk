<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a single audit trail entry for a ticket lifecycle event.
 *
 * @property int    $id
 * @property int    $ticket_id
 * @property int    $user_id
 * @property string $action
 * @property string $description
 */
class ActivityLog extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'action',
        'description',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
