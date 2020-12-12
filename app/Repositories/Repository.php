<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use App\Utilities\General;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\Paginator;

/**
 * Class Repository
 * @package App\Repositories
 */
abstract class Repository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Toggle config cache.
     * @var bool
     */
    protected $cached = false;

    /**
     * Specify Model class name
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws Exception
     */
    public function model()
    {
        return $this->makeModel();
    }

    /**
     * Specify Model class name
     *
     * @return Builder
     * @throws Exception
     */
    public function query()
    {
        return $this->makeModel()->newQuery();
    }

    /**
     * Make model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws Exception
     */
    private function makeModel()
    {
        if (empty($this->model)) {
            throw new Exception("Must assign property `model` to a Model class");
        }

        $model = app()->make($this->model);

        if (!$model instanceof \Illuminate\Database\Eloquent\Model) {
            $modelClass = $this->model;
            throw new Exception("Class {$modelClass} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $model;
    }

    /**
     * Get a single column's value from the first result of a query.
     *
     * @param string $column
     * @param array $where
     * @return string|int|mixed
     * @throws Exception
     */
    public function value($column, array $where = [])
    {
        $model = $this->model();

        if ($where) {
            $model->where($where);
        }

        return $model->value($column);
    }

    /**
     * Get a single column's value from the first result of a query.
     *
     * @param string $column
     * @param array $where
     * @param string|null $key
     * @return string|int|mixed
     * @throws Exception
     */
    public function values($column, $key = null, array $where = [])
    {
        $model = $this->model();

        if ($where) {
            $model->where($where);
        }

        return $model->pluck($column, $key);
    }

    /**
     * Get max value of column.
     *
     * @param string $column
     * @param array $where
     * @return int|string
     * @throws Exception
     */
    public function max($column, array $where = [])
    {
        $model = $this->model();

        if ($where) {
            $model->where($where);
        }

        return $model->max($column);
    }

    /**
     * Get max value of column.
     *
     * @param string $column
     * @param array $where
     * @return int|string
     * @throws Exception
     */
    public function min($column, array $where = [])
    {
        $model = $this->model();

        if ($where) {
            $model->where($where);
        }

        return $model->min($column);
    }

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     * @return bool
     * @throws Exception
     */
    public function exists(array $where, $columns = ['*'])
    {
        $model = $this->model()->existsWhere($where, $columns);

        return $model;
    }

    /**
     * Find data by id
     *
     * @param int|string $id
     * @param array $columns
     * @return Model|mixed
     * @throws Exception
     */
    public function find($id, $columns = ['*'])
    {
        $model = $this->model()->find($id, $columns);

        return $model;
    }

    /**
     * Find multiple models by their primary keys.
     *
     * @param array $ids
     * @param array $columns
     * @return Collection
     * @throws Exception
     */
    public function findMany($ids, $columns = ['*'])
    {
        $model = $this->model()->findMany($ids, $columns);

        return $model;
    }

    /**
     * Find data by field and value
     *
     * @param string $field
     * @param int|string $value
     * @param array $columns
     * @return Model
     * @throws Exception
     */
    public function findBy($field, $value = null, $columns = ['*'])
    {
        $model = $this->model()->findBy($field, $value, $columns);

        return $model;
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param int|string $id
     * @param array $columns
     * @return Collection
     * @throws Exception|\Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        $model = $this->model()->findOrFail($id, $columns);

        return $model;
    }

    /**
     * Find a model by its primary key or return fresh model instance.
     *
     * @param mixed $id
     * @param array $columns
     * @return Model
     * @throws Exception
     */
    public function findOrNew($id, $columns = ['*'])
    {
        $model = $this->model()->findOrNew($id, $columns);

        return $model;
    }

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     * @return Model
     * @throws Exception
     */
    public function first(array $where = [], $columns = ['*'])
    {
        if ($where) {
            $model = $this->model()->findWhere($where, $columns);
        } else {
            $model = $this->model()->first($columns);
        }

        return $model;
    }

    /**
     * Get the first record matching the attributes or instantiate it.
     *
     * @param array $attributes
     * @param array $values
     * @return Model
     * @throws Exception
     */
    public function firstOrNew(array $attributes, array $values = [])
    {
        $model = $this->model()->firstOrNew($attributes, $values);

        return $model;
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param array $attributes
     * @param array $values
     * @return Model
     * @throws Exception
     */
    public function firstOrCreate(array $attributes, array $values = [])
    {
        $model = $this->model()->firstOrCreate($attributes, $values);

        return $model;
    }

    /**
     * Get the first result or throw an exception.
     *
     * @param array $columns
     * @return Model
     * @throws ModelNotFoundException|Exception
     */
    public function firstOrFail($columns = ['*'])
    {
        $model = $this->model()->firstOrFail($columns);

        return $model;
    }

    /**
     * Get the first result or call a callback.
     *
     * @param \Closure|array $columns
     * @param \Closure|null $callback
     * @return Model
     * @throws Exception
     */
    public function firstOrDo($columns = ['*'], \Closure $callback = null)
    {
        $model = $this->model()->firstOr($columns, $callback);

        return $model;
    }

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     * @param array $ids
     * @return Collection|Model[]
     * @throws Exception
     */
    public function all($ids = [], $columns = ['*'])
    {
        if ($this->cached) {
            $cacheKey = General::hexKey([__METHOD__, $ids, $columns]);

            $cachedData = cache($cacheKey, []);

            if (!empty($cachedData)) {
                return $cachedData;
            }
        }

        if ($ids) {
            $results = $this->model()->whereIn($this->model()->getKeyName(), $ids)->get($columns);
        } else {
            $results = $this->model()->all($columns);
        }

        if (isset($cacheKey)) {
            cache([$cacheKey => $results], now()->addMinutes(config('database.cache_time', 1)));
        }

        return $results;
    }

    /**
     * Find all data by multiple fields
     *
     * @param array $where
     * @param array $columns
     * @param array $orders
     * @return Collection
     * @throws Exception
     */
    public function get(array $where, $columns = ['*'], array $orders = [])
    {
        $model = $this->model()->getWhere($where, $columns, $orders);

        return $model;
    }

    /**
     * Find data by multiple values in one field
     *
     * @param string $field
     * @param array $values
     * @param array $columns
     * @return Collection
     * @throws Exception
     */
    public function getIn($field, array $values, $columns = ['*'])
    {
        $model = $this->model()->getWhereIn($field, $values, $columns);

        return $model;
    }

    /**
     * Find data by excluding multiple values in one field
     *
     * @param string $field
     * @param array $values
     * @param array $columns
     * @return Collection
     * @throws Exception
     */
    public function getNotIn($field, array $values, $columns = ['*'])
    {
        $model = $this->model()->getWhereNotIn($field, $values, $columns);

        return $model;
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param int|null $limit
     * @param array $columns
     * @param array $orders
     * @return Paginator
     * @throws Exception
     */
    public function paginate($limit = null, $columns = ['*'], $orders = [])
    {
        $limit = is_null($limit) ? config('database.pagination.limit', 15) : $limit;

        $model = $this->model();

        foreach ($orders as $column => $direction) {
            $model->orderBy($column, $direction ?: 'asc');
        }

        $results = $model->paginate($limit, $columns);

        $results->appends(request()->query());

        return $results;
    }

    /**
     * Retrieve all data of repository, simple paginated
     *
     * @param int|null $limit
     * @param array $columns
     * @return Paginator
     * @throws Exception
     */
    public function simplePaginate($limit = null, $columns = ['*'])
    {
        return $this->model()->simplePaginate($limit, $columns);
    }

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     * @return mixed
     * @throws Exception
     */
    public function create(array $attributes)
    {
        $model = $this->model()->create($attributes);

        return $model;
    }

    /**
     * Insert multi rows.
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function insert($data)
    {
        return $this->model()->insert($data);
    }

    /**
     * Insert multi rows touch update created_at, updated_at.
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function insertTouch($data)
    {
        return $this->model()->insertTouch($data);
    }

    /**
     * Insert multi rows get collections modes.
     *
     * @param array $data
     * @return \Illuminate\Support\Collection
     * @throws Exception
     */
    public function insertMany($data)
    {
        $collections = collect();

        foreach ($data as $datum) {
            $collections->push($this->create($datum));
        }

        return $collections;
    }

    /**
     * Update a entity in repository by id
     *
     * @param array $attributes
     * @param int|string $id
     * @return Model
     * @throws Exception
     */
    public function update($id, array $attributes)
    {
        $model = $this->model()->findOrFail($id);
        $model->fill($attributes);
        $model->save();

        return $model;
    }

    /**
     * Update by conditions
     *
     * @param array $attributes
     * @param array $where
     * @return int
     * @throws Exception
     */
    public function updateWhere(array $where, array $attributes)
    {
        return $this->model()->updateWhere($attributes, $where);
    }

    /**
     * Update first by conditions
     *
     * @param array $attributes
     * @param array $where
     * @return Model
     * @throws Exception
     */
    public function updateFirst(array $where, array $attributes)
    {
        $model = $this->model()->updateFirst($attributes, $where);

        return $model;
    }

    /**
     * Update or Create an entity in repository
     *
     * @param array $attributes
     * @param array $values
     * @return Model
     * @throws Exception
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->model()->updateOrCreate($attributes, $values);
    }

    /**
     * Delete a entity in repository by id
     *
     * @param int|string $id
     * @return int
     * @throws Exception
     */
    public function delete($id)
    {
        $model = $this->find($id);

        $deleted = $model->delete();

        return $deleted;
    }

    /**
     * Delete multiple entities by given criteria.
     *
     * @param array $where
     * @return int
     * @throws Exception
     */
    public function deleteWhere(array $where)
    {
        return $this->model()->deleteWhere($where);
    }

    /**
     * Sync relations
     *
     * @param int|string $id
     * @param string $relation
     * @param array $attributes
     * @param bool $detaching
     * @return Model
     * @throws Exception
     */
    public function sync($id, $relation, $attributes, $detaching = true)
    {
        return $this->model()->findToSync($id, $relation, $attributes, $detaching);
    }

    /**
     * SyncWithoutDetaching
     *
     * @param int|string $id
     * @param string $relation
     * @param array $attributes
     * @return Model
     * @throws Exception
     */
    public function syncWithoutDetaching($id, $relation, $attributes)
    {
        return $this->findToSync($id, $relation, $attributes, false);
    }

    /**
     * Set new auto increment number.
     *
     * @param int $id
     * @throws Exception
     */
    public function setAutoIncrement($id)
    {
        $this->model()->setAutoIncrement($id);
    }

    /**
     * Get auto increment id.
     *
     * @return int
     * @throws Exception
     */
    public function getAutoIncrement()
    {
        return $this->model()->getAutoIncrement();
    }

    /**
     * Where in
     *
     * @param $column
     * @param $values
     * @param string $boolean
     * @param bool $not
     * @return Model
     * @throws Exception
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        return $this->model()->whereIn($column, $values, $boolean, $not);
    }
}
