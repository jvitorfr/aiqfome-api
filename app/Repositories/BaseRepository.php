<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract readonly class BaseRepository
{
    public function __construct(
        protected Model $model
    ) {
    }

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function all(): Collection
    {
        return $this->query()->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage);
    }

    public function find(int|string $id): ?Model
    {
        return $this->query()->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        return $model;
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }

    public function where(string $column, mixed $operator = null, mixed $value = null): Builder
    {
        return $this->query()->where(...func_get_args());
    }

    public function whereIn(string $column, array $values): Builder
    {
        return $this->query()->whereIn($column, $values);
    }

    public function with(array|string $relations): Builder
    {
        return $this->query()->with($relations);
    }
}
