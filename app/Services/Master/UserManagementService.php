<?php

namespace App\Services\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserManagementService extends MasterDataService
{
    protected function model(): string
    {
        return User::class;
    }

    protected function searchColumns(): array
    {
        return ['name', 'email'];
    }

    protected function applyDefaultOrder(Builder $query): Builder
    {
        return $query->orderBy('name');
    }
}
