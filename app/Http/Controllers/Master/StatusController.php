<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\StoreStatusRequest;
use App\Http\Requests\Master\UpdateStatusRequest;
use App\Models\Status;
use App\Services\Master\StatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatusController extends MasterDataController
{
    public function __construct(private readonly StatusService $statusService) {}

    protected function service(): StatusService { return $this->statusService; }
    protected function viewPath(): string       { return 'master.statuses'; }
    protected function routeName(): string      { return 'master.statuses'; }
    protected function resourceLabel(): string  { return 'Status'; }

    public function index(Request $request): View
    {
        return $this->respondIndex($request);
    }

    public function store(StoreStatusRequest $request): RedirectResponse
    {
        $data              = $request->validated();
        $data['is_default'] = $request->boolean('is_default');
        $data['is_active']  = $request->boolean('is_active');

        return $this->respondStore($data);
    }

    public function update(UpdateStatusRequest $request, Status $status): RedirectResponse
    {
        $data              = $request->validated();
        $data['is_default'] = $request->boolean('is_default');
        $data['is_active']  = $request->boolean('is_active');

        return $this->respondUpdate($status, $data);
    }

    public function destroy(Status $status): RedirectResponse
    {
        return $this->respondDestroy($status);
    }
}