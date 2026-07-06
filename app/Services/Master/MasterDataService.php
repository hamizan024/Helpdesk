<?php

namespace App\Services\Master;

use App\Models\MasterLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Abstract base for all Master Data services.
 *
 * Subclasses must declare which Eloquent model they manage and which columns
 * are searched. All CRUD mutations are automatically recorded in master_logs.
 */
abstract class MasterDataService
{
    /** FQCN of the managed model, e.g. App\Models\Department::class */
    abstract protected function model(): string;

    /** Columns to match against the search term (LIKE). */
    abstract protected function searchColumns(): array;

    /** Override to customise ordering (default: latest first). */
    protected function applyDefaultOrder(Builder $query): Builder
    {
        return $query->latest();
    }

    /** Override to add eager-loading or extra constraints to every list query. */
    protected function baseQuery(): Builder
    {
        return ($this->model())::query();
    }

    public function list(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->applyDefaultOrder($this->baseQuery());

        if (!empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                foreach ($this->searchColumns() as $column) {
                    $q->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function store(array $data, User $user): Model
    {
        $instance = ($this->model())::create($data);
        $this->log($user, $instance, 'created', null, $data);

        return $instance;
    }

    public function update(Model $instance, array $data, User $user): Model
    {
        $old = $instance->toArray();
        $instance->update($data);
        $this->log($user, $instance->fresh(), 'updated', $old, $data);

        return $instance->fresh();
    }

    public function delete(Model $instance, User $user): void
    {
        $old = $instance->toArray();
        $this->log($user, $instance, 'deleted', $old, null);
        $instance->delete();
    }

    protected function log(User $user, Model $instance, string $action, ?array $old, ?array $new): void
    {
        MasterLog::create([
            'loggable_type' => get_class($instance),
            'loggable_id'   => $instance->getKey(),
            'action'        => $action,
            'user_id'       => $user->id,
            'old_values'    => $old,
            'new_values'    => $new,
        ]);
    }
}