<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\StorePriorityRequest;
use App\Http\Requests\Master\UpdatePriorityRequest;
use App\Models\Priority;
use App\Services\Master\PriorityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PriorityController extends MasterDataController
{
    public function __construct(private readonly PriorityService $priorityService) {}

    protected function service(): PriorityService { return $this->priorityService; }
    protected function viewPath(): string         { return 'master.priorities'; }
    protected function routeName(): string        { return 'master.priorities'; }
    protected function resourceLabel(): string    { return 'Priority'; }

    public function index(Request $request): View
    {
        return $this->respondIndex($request);
    }

    public function store(StorePriorityRequest $request): RedirectResponse
    {
        $data             = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        return $this->respondStore($data);
    }

    public function update(UpdatePriorityRequest $request, Priority $priority): RedirectResponse
    {
        $data             = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        return $this->respondUpdate($priority, $data);
    }

    public function destroy(Priority $priority): RedirectResponse
    {
        return $this->respondDestroy($priority);
    }
}