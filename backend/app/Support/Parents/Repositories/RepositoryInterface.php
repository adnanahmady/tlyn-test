<?php

namespace App\Support\Parents\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @template TModel of Model
 */
interface RepositoryInterface
{
    /** @return Collection<TModel> */
    public function all(): Collection;

    /**
     * @return Model<TModel>|null
     */
    public function find(string $id): ?Model;

    /**
     * @param int|Model<TModel> $model
     */
    public function delete(int|Model $model): bool;
}
