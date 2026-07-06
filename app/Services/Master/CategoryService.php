<?php

namespace App\Services\Master;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class CategoryService extends MasterDataService
{
    protected function model(): string
    {
        return Category::class;
    }

    protected function searchColumns(): array
    {
        return ['name', 'description'];
    }

    protected function baseQuery(): Builder
    {
        return Category::with('department');
    }
}