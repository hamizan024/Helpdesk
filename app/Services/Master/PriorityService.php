<?php

namespace App\Services\Master;

use App\Models\Priority;
use Illuminate\Database\Eloquent\Builder;

class PriorityService extends MasterDataService
{
    protected function model(): string
    {
        return Priority::class;
    }

    protected function searchColumns(): array
    {
        return ['name'];
    }

    protected function applyDefaultOrder(Builder $query): Builder
    {
        return $query->orderBy('level');
    }
}