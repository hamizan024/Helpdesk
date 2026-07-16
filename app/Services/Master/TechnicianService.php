<?php

namespace App\Services\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class TechnicianService extends MasterDataService
{
    protected function model(): string
    {
        return User::class;
    }

    protected function searchColumns(): array
    {
        return ['name', 'email'];
    }

    protected function baseQuery(): Builder
    {
        return User::query()->where('role', 'technician')->with('departments');
    }

    protected function applyDefaultOrder(Builder $query): Builder
    {
        return $query->orderBy('name');
    }

    /**
     * Replace the technician's department memberships and log the change.
     */
    public function updateDepartments(User $technician, array $departmentIds, User $actor): User
    {
        $old = $technician->departments->pluck('id')->all();

        $technician->departments()->sync($departmentIds);

        $this->log($actor, $technician->fresh(), 'updated', ['department_ids' => $old], ['department_ids' => $departmentIds]);

        return $technician->fresh();
    }
}
