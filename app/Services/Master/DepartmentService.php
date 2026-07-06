<?php

namespace App\Services\Master;

use App\Models\Department;

class DepartmentService extends MasterDataService
{
    protected function model(): string
    {
        return Department::class;
    }

    protected function searchColumns(): array
    {
        return ['name', 'description'];
    }
}