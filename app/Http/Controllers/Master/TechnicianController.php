<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\UpdateTechnicianRequest;
use App\Models\Department;
use App\Models\User;
use App\Services\Master\TechnicianService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TechnicianController extends Controller
{
    public function __construct(private readonly TechnicianService $technicianService) {}

    public function index(Request $request): View
    {
        $search      = (string) $request->query('search', '');
        $items       = $this->technicianService->list($search);
        $departments = Department::active()->orderBy('name')->get();

        return view('master.technicians.index', compact('items', 'search', 'departments'));
    }

    public function update(UpdateTechnicianRequest $request, User $technician): RedirectResponse
    {
        $this->technicianService->updateDepartments(
            $technician,
            $request->validated()['department_ids'] ?? [],
            auth()->user(),
        );

        return redirect()
            ->route('master.technicians.index')
            ->with('success', 'Technician updated successfully.');
    }
}
