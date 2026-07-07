<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\StoreCategoryRequest;
use App\Http\Requests\Master\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Department;
use App\Services\Master\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends MasterDataController
{
    public function __construct(private readonly CategoryService $categoryService) {}

    protected function service(): CategoryService { return $this->categoryService; }
    protected function viewPath(): string         { return 'master.categories'; }
    protected function routeName(): string        { return 'master.categories'; }
    protected function resourceLabel(): string    { return 'Category'; }

    public function index(Request $request): View
    {
        $search      = (string) $request->query('search', '');
        $items       = $this->service()->list($search);
        $departments = Department::active()->orderBy('name')->get();

        return view('master.categories.index', compact('items', 'search', 'departments'));
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data             = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        return $this->respondStore($data);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data             = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        return $this->respondUpdate($category, $data);
    }

    public function destroy(Category $category): RedirectResponse
    {
        return $this->respondDestroy($category);
    }
}