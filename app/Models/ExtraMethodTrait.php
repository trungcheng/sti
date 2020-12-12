<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait ExtraMethodTrait
 * @package App\Models
 */
trait ExtraMethodTrait
{
    /**
     * Sync relations
     *
     * @param int|string $id
     * @param string $relation
     * @param array $attributes
     * @param bool $detaching
     * @return Model|static
     * @throws Exception
     */
    public static function findToSync($id, $relation, $attributes, $detaching = true)
    {
        return static::find($id)->{$relation}()->sync($attributes, $detaching);
    }

    /**
     * Retrieve all data of repository, simple paginated
     *
     * @param int|null $limit
     * @param array $columns
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public static function simplePaginate($limit = null, $columns = ['*'])
    {
        return static::paginate($limit, $columns, "simplePaginate");
    }

    /**
     * Find data by field and value
     *
     * @param string $field
     * @param null $value
     * @param array $columns
     * @return Model|static
     */
    public static function findBy($field, $value = null, $columns = ['*'])
    {
        return static::where($field, '=', $value)->first($columns);
    }

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     * @return Model|static
     * @throws Exception
     */
    public static function findWhere(array $where, $columns = ['*'])
    {
        return static::where($where)->first($columns);
    }

    /**
     * Get all data by multiple fields
     *
     * @param array $where
     * @param array $columns
     * @param array $orders
     * @return Collection
     */
    public static function getWhere(array $where, $columns = ['*'], array $orders = [])
    {
        $model = static::where($where);

        if ($orders) {
            foreach ($orders as $column => $direction) {
                $model->orderBy($column, $direction ?: 'asc');
            }
        }

        return $model->get($columns);
    }

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     * @return bool
     * @throws Exception
     */
    public static function existsWhere(array $where, $columns = ['*'])
    {
        return static::where($where)->exists($columns);
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
    public static function getWhereIn($field, array $values, $columns = ['*'])
    {
        return static::whereIn($field, $values)->get($columns);
    }

    /**
     * Find data by excluding multiple values in one field
     *
     * @param string $field
     * @param array $values
     * @param array $columns
     * @return Model
     * @throws Exception
     */
    public static function getWhereNotIn($field, array $values, $columns = ['*'])
    {
        return static::whereNotIn($field, $values)->first($columns);
    }

    /**
     * Update a entity in repository by id
     *
     * @param array $attributes
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public static function updateFind(array $attributes, $id)
    {
        $model = static::findOrFail($id);
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
    public static function updateWhere(array $where, array $attributes)
    {
        return static::where($where)->update($attributes);
    }

    /**
     * Update first by conditions
     *
     * @param array $attributes
     * @param array $where
     * @return Model|static
     */
    public static function updateFirst(array $where, array $attributes)
    {
        $model = static::where($where)->first();
        $model->fill($attributes);

        return $model->save();
    }

    /**
     * Delete multiple entities by given criteria.
     *
     * @param array $where
     * @return int
     * @throws Exception
     */
    public static function deleteWhere(array $where)
    {
        return static::where($where)->delete();
    }

    /**
     * Set new auto increment number.
     *
     * @param int $id
     * @throws Exception
     */
    public static function setAutoIncrement($id)
    {
        $tableName = static::getTable();

        DB::update("ALTER TABLE {$tableName} AUTO_INCREMENT = {$id};");
    }

    /**
     * Get auto increment id.
     *
     * @throws Exception
     */
    public static function getAutoIncrement()
    {
        $tableName = static::getTable();

        $statement = DB::select("SHOW TABLE STATUS LIKE '{$tableName}';");

        return $statement[0]->Auto_increment;
    }

    /**
     * Insert data.
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public static function insertTouch($data)
    {
        if (!is_array($data)) {
            $data = [$data];
        }

        if (static::CREATED_AT or static::UPDATED_AT) {
            foreach ($data as &$row) {
                if (static::CREATED_AT and empty($row[static::CREATED_AT])) {
                    $row[static::CREATED_AT] = now();
                }

                if (static::UPDATED_AT and empty($row[static::UPDATED_AT])) {
                    $row[static::UPDATED_AT] = now();
                }
            }
        }

        return static::insert($data);
    }
}
