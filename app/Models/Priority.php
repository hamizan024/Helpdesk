<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $name
 * @property string $color  Bootstrap badge color (danger, warning, primary, secondary …)
 * @property int    $level  Sort order; lower = higher urgency
 * @property bool   $is_active
 */
class Priority extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'level', 'is_active'];

    protected function casts(): array
    {
        return [
            'level'     => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('level');
    }
}