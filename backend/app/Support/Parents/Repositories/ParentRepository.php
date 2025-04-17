<?php

namespace App\Support\Parents\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @template TModel of Model
 */
abstract class ParentRepository implements RepositoryInterface
{
    /** @return Model<TModel> */
    abstract protected function model(): Model;

    /** @return Collection<TModel> */
    public function all(): Collection
    {
        return $this->model()->all();
    }

    /**
     * @return Model<TModel>|null
     */
    public function find(string $id): ?Model
    {
        return $this->model()->find($id);
    }

    /**
     * @param int|Model<TModel> $model
     */
    public function delete(int|Model $model): bool
    {
        $id = is_int($model) ? $model : $model->getKey();

        return (bool) $this->find($id)->delete();
    }
}
