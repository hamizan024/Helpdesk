<?php

namespace App\Services\Master;

use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StatusService extends MasterDataService
{
    protected function model(): string
    {
        return Status::class;
    }

    protected function searchColumns(): array
    {
        return ['name'];
    }

    /**
     * Before creating a new default status, demote any existing default.
     */
    public function store(array $data, User $user): Model
    {
        if (!empty($data['is_default'])) {
            Status::where('is_default', true)->update(['is_default' => false]);
        }

        return parent::store($data, $user);
    }

    /**
     * Before updating to become the default status, demote any other default.
     */
    public function update(Model $instance, array $data, User $user): Model
    {
        if (!empty($data['is_default'])) {
            Status::where('is_default', true)
                ->where('id', '!=', $instance->id)
                ->update(['is_default' => false]);
        }

        return parent::update($instance, $data, $user);
    }
}