<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int         $id
 * @property string      $name
 * @property string|null $description
 * @property int|null    $department_id
 * @property string|null $default_priority  Low|Medium|High — applied to tickets created under this category
 * @property bool        $is_active
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'department_id', 'default_priority', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}