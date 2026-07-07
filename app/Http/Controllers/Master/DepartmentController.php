<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\StoreDepartmentRequest;
use App\Http\Requests\Master\UpdateDepartmentRequest;
use App\Models\Department;
use App\Services\Master\DepartmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends MasterDataController
{
    public function __construct(private readonly DepartmentService $departmentService) {}

    protected function service(): DepartmentService { return $this->departmentService; }
    protected function viewPath(): string           { return 'master.departments'; }
    protected function routeName(): string          { return 'master.departments'; }
    protected function resourceLabel(): string      { return 'Department'; }

    public function index(Request $request): View
    {
        return $this->respondIndex($request);
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $data             = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        return $this->respondStore($data);
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $data             = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        return $this->respondUpdate($department, $data);
    }

    public function destroy(Department $department): RedirectResponse
    {
        return $this->respondDestroy($department);
    }
}