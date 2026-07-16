<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\StoreUserRequest;
use App\Http\Requests\Master\UpdateUserRequest;
use App\Models\User;
use App\Services\Master\UserManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends MasterDataController
{
    public function __construct(private readonly UserManagementService $userManagementService) {}

    protected function service(): UserManagementService { return $this->userManagementService; }
    protected function viewPath(): string                { return 'master.users'; }
    protected function routeName(): string               { return 'master.users'; }
    protected function resourceLabel(): string            { return 'User'; }

    public function index(Request $request): View
    {
        $search = (string) $request->query('search', '');
        $items  = $this->service()->list($search);
        $items->getCollection()->loadCount(['tickets', 'assignedTickets']);

        return view('master.users.index', compact('items', 'search'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        return $this->respondStore($request->validated());
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $this->respondUpdate($user, $data);
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return redirect()
                ->route('master.users.index')
                ->with('error', 'Anda tidak bisa menghapus akun Anda sendiri dari halaman ini.');
        }

        return $this->respondDestroy($user);
    }
}
