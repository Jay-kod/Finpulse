<?php

namespace App\Shared\Core\Repositories;

use App\Shared\Core\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * The Eloquent model instance.
     */
    protected Model $model;

    /**
     * Create a new repository instance.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records.
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find a record by ID.
     */
    public function find(int|string $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find a record by ID or throw an exception.
     */
    public function findOrFail(int|string $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new record.
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a record by ID.
     */
    public function update(int|string $id, array $data): bool
    {
        $record = $this->findOrFail($id);

        return $record->update($data);
    }

    /**
     * Delete a record by ID.
     */
    public function delete(int|string $id): bool
    {
        $record = $this->findOrFail($id);

        return $record->delete();
    }

    /**
     * Paginate results.
     */
    public function paginate(int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? config('sentiment.pagination.per_page', 25);

        return $this->model->paginate($perPage);
    }

    /**
     * Find records matching given criteria.
     */
    public function findWhere(array $criteria): Collection
    {
        $query = $this->model->newQuery();

        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }

        return $query->get();
    }

    /**
     * Get the first record matching given criteria.
     */
    public function findFirstWhere(array $criteria): ?Model
    {
        $query = $this->model->newQuery();

        foreach ($criteria as $field => $value) {
            $query->where($field, $value);
        }

        return $query->first();
    }

    /**
     * Count all records or those matching criteria.
     */
    public function count(array $criteria = []): int
    {
        if (empty($criteria)) {
            return $this->model->count();
        }

        $query = $this->model->newQuery();

        foreach ($criteria as $field => $value) {
            $query->where($field, $value);
        }

        return $query->count();
    }

    /**
     * Get a new query builder instance.
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }
}
