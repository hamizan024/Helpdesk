<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\MasterDataService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Abstract base for every Master Data resource controller.
 *
 * Subclasses provide the service, view path, route prefix, and resource label.
 * Concrete index/store/update/destroy methods delegate here after resolving
 * their type-hinted FormRequest and route-bound model.
 *
 * Access is restricted to administrators via the 'admin' route middleware
 * applied to the master.* route group in routes/web.php.
 */
abstract class MasterDataController extends Controller
{
    abstract protected function service(): MasterDataService;

    /** Blade view directory, e.g. 'master.departments' */
    abstract protected function viewPath(): string;

    /** Named route prefix, e.g. 'master.departments' */
    abstract protected function routeName(): string;

    /** Human-readable label shown in flash messages, e.g. 'Department' */
    abstract protected function resourceLabel(): string;

    protected function respondIndex(Request $request): View
    {
        $search = (string) $request->query('search', '');
        $items  = $this->service()->list($search);

        return view("{$this->viewPath()}.index", compact('items', 'search'));
    }

    protected function respondStore(array $validated): RedirectResponse
    {
        $this->service()->store($validated, auth()->user());

        return redirect()
            ->route("{$this->routeName()}.index")
            ->with('success', "{$this->resourceLabel()} created successfully.");
    }

    protected function respondUpdate(Model $instance, array $validated): RedirectResponse
    {
        $this->service()->update($instance, $validated, auth()->user());

        return redirect()
            ->route("{$this->routeName()}.index")
            ->with('success', "{$this->resourceLabel()} updated successfully.");
    }

    protected function respondDestroy(Model $instance): RedirectResponse
    {
        $this->service()->delete($instance, auth()->user());

        return redirect()
            ->route("{$this->routeName()}.index")
            ->with('success', "{$this->resourceLabel()} deleted successfully.");
    }
}