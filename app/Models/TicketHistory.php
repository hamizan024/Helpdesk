<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Records a single field-level change made to a ticket.
 *
 * @property int         $id
 * @property int         $ticket_id
 * @property int         $user_id
 * @property string      $field
 * @property string|null $old_value
 * @property string|null $new_value
 */
class TicketHistory extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'field',
        'old_value',
        'new_value',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFieldLabel(): string
    {
        return match ($this->field) {
            'title'       => 'Title',
            'description' => 'Description',
            'priority'    => 'Priority',
            'status'      => 'Status',
            'assigned_to' => 'Assigned To',
            'category_id' => 'Category',
            'due_date'    => 'Due Date',
            default       => ucfirst(str_replace('_', ' ', $this->field)),
        };
    }
}