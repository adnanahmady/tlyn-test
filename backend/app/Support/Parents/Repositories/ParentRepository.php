<?php

namespace App\Support\Parents\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @template TModel of Model
 *
 * @implements RepositoryInterface<TModel>
 */
abstract class ParentRepository implements RepositoryInterface
{
    /** @return TModel */
    abstract protected function model(): Model;

    /** @return Collection<int, TModel> */
    public function all(): Collection
    {
        return $this->model()->all();
    }

    /** @return TModel|null */
    public function find(string $id): ?Model
    {
        return $this->model()->find($id);
    }

    /** @param int|TModel $model */
    public function delete(int|Model $model): bool
    {
        $id = is_int($model) ? $model : $model->getKey();

        return (bool) $this->find($id)->delete();
    }

    /**
     * @return TModel|null
     */
    public function findAndLock(int $id): ?Model
    {
        return $this->model()->where('id', $id)->lockForUpdate()->first();
    }
}
