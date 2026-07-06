<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Append-only audit log for all Master Data CRUD operations.
 *
 * @property int         $id
 * @property string      $loggable_type
 * @property int         $loggable_id
 * @property string      $action  created|updated|deleted
 * @property int         $user_id
 * @property array|null  $old_values
 * @property array|null  $new_values
 */
class MasterLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'loggable_type',
        'loggable_id',
        'action',
        'user_id',
        'old_values',
        'new_values',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}