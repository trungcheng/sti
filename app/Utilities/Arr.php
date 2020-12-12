<?php

namespace App\Utilities;


class Arr extends \Illuminate\Support\Arr
{
    /**
     * Move an item of array to new position.
     *
     * @param array $array
     * @param int $sign key or position of item
     * @param int $newPosition new position was moved.
     * @param bool $positionFlag Set true if wanth move by old position to new postion.
     * @return array
     */
    public static function move(array $array, $sign, $newPosition, $positionFlag = false)
    {
        // Default position is 0.
        $position = 0;

        // Default result is empty array.
        $resultArray = [];

        // With $sign is position.
        if ($positionFlag) {
            // Has key by postion.
            $movedKey = array_keys($array)[$sign];

            // Add data to result array.
            foreach ($array as $key => $item) {
                if ($position == $newPosition) {
                    $resultArray[$movedKey] = $array[$movedKey];
                }

                if ($position != $sign) {
                    $resultArray[$key] = $item;
                }

                $position++;
            }
            // With $sign is key.
        } else {
            // Add data to result array.
            foreach ($array as $key => $item) {
                if ($position == $newPosition) {
                    $resultArray[$sign] = $array[$sign];
                }

                if ($key != $sign) {
                    $resultArray[$key] = $item;
                }

                $position++;
            }
        }

        return $resultArray;
    }

    /**
     * Rename keys of array.
     *
     * @param array $array
     * @param array $mapName EX: ['old_key' => 'new_key', ...]
     * @return array
     */
    public static function renameKeys(array $array, $mapName)
    {
        // Default result is empty array.
        $newArray = [];

        // Add data to result array.
        foreach ($array as $key => $value) {
            if (array_key_exists($key, $mapName)) {
                $newArray[$mapName[$key]] = $value;
            } else {
                $newArray[$key] = $value;
            }
        }

        return $newArray;
    }

    /**
     * Merge array2 to array1 if same key in array1
     *
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public static function leftMerge(array $array1, array $array2)
    {
        foreach ($array2 as $key => $value) {
            if (array_key_exists($key, $array1)) {
                $array1[$key] = $value;
            }
        }

        return $array1;
    }

    /**
     * Vertical rotate a array.
     *
     * @param array $flatten
     * @return array Vertical rotate a array.
     */
    public static function rotate(array $flatten)
    {
        return call_user_func_array('array_map', array_merge([null], $flatten));
    }

    /**
     * Parse to full array.
     * @param mixed $list
     * @return array converted
     */
    public static function cast($list)
    {
        if (is_array($list)) {
            // Parse item of list to array.
            array_walk_recursive(
                $list,
                function (&$item) {
                    if (is_object($item)) {
                        $item = (array)$item;
                    }
                }
            );
        } else {
            // Pasre list to array.
            $list = (array)$list;
        }

        return $list;
    }

    /**
     * Filter flatten array by condition.
     *
     * @param array $flatten multi-dimensional array (record set) from which to pull a column of values.
     * @param array $condition EX: ['id' => 1, 'name' => 'John']
     * @return array filtered
     */
    public static function flattenFilter($flatten, $condition)
    {
        // Default result is empty array.
        $result = [];

        // Add data to result array.
        foreach ($flatten as $key => $item) {
            $checker = true;

            foreach ($condition as $field => $value) {
                $checker &= $item[$field] == $value;
            }

            if ($checker) {
                $result[$key] = $item;
            }
        }

        return $result;
    }

    /**
     * Soft flatten array.
     *  Example: $arr2 = array_msort($data, ['collumn1'=>SORT_DESC, 'collumn2'=>SORT_ASC]);
     *
     * @param array $array
     * @param array $cols
     * @return array
     */
    public static function flattenSort(array $array, array $cols)
    {
        $colarr = [];

        foreach ($cols as $col => $order) {
            $colarr[$col] = [];

            foreach ($array as $k => $row) {
                $colarr[$col]['_' . $k] = strtolower($row[$col]);
            }
        }

        $eval = 'array_multisort(';

        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\'' . $col . '\'],' . $order . ',';
        }

        $eval = substr($eval, 0, -1) . ');';

        eval($eval);

        $ret = [];

        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k, 1);

                if (!isset($ret[$k])) {
                    $ret[$k] = $array[$k];
                }

                $ret[$k][$col] = $array[$k][$col];
            }
        }

        return $ret;
    }

    /**
     * Soft flatten array by one collumn
     *
     * @param array $flatten
     * @param string $columnKey
     * @param int $sort SORT_ASC or SORT_DESC
     * @return mixed
     */
    public static function flattenSimpleSort($flatten, $columnKey, $sort = SORT_ASC)
    {
        usort(
            $flatten,
            function ($item1, $item2) use ($columnKey, $sort) {
                if ($sort == SORT_ASC) {
                    return $item1[$columnKey] - $item2[$columnKey];
                } else {
                    return $item2[$columnKey] - $item1[$columnKey];
                }
            }
        );

        return $flatten;
    }

    /**
     * Convert array to object recursive.
     *
     * @param array $array
     * @return object
     */
    public static function toObject(array $array)
    {
        return json_decode(json_encode($array), false);
    }

    /**
     * Check target is flatten.
     *
     * @param array $array
     * @return bool
     */
    public static function isFlatten($array)
    {
        return (is_array($array) and is_array(reset($array)));
    }

    /**
     * Check value is flatten.
     *
     * @param array $flatten
     * @param bool $relatively .
     * @return bool
     */
    public static function isDbResult($flatten, $relatively = true)
    {
        // Target is not array.
        if (!is_array($flatten)) {
            return false;
        }

        // Flatten 1 item.
        if (count($flatten) === 1 and is_array(reset($flatten))) {
            return true;
        }

        // Check relatively.
        if ($relatively) {
            $current = current($flatten);
            $next = next($flatten);
            $end = end($flatten);

            if (
                !is_array($current)
                or !is_array($next)
                or !is_array($end)
                or ($keys = array_keys($current)) != array_keys($next)
                or $keys != array_keys($end)
            ) {
                return false;
            }
        } else {
            // Check 100%.
            while ($current = current($flatten) and $next = next($flatten)) {
                if (!is_array($current) or !is_array($next) or array_keys($current) != array_keys($next)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get try get value by list keys.
     *
     * @param array $array
     * @param array $keys
     * @return mixed|null
     */
    public static function try(array $array, array $keys)
    {
        foreach ($keys as $key) {
            if (isset($array[$key])) {
                return $array[$key];
            }
        }

        return null;
    }

    /**
     * Using id column as key.
     *
     * @param array $array
     * @param string $idName
     * @return array
     */
    public static function keyBy(array $array, $idName = 'id')
    {
        $result = [];

        foreach ($array as $item) {
            $result[$idName] = $item;
        }

        return $result;
    }

    /**
     * Cast string value in array to corresponding data type.
     *
     * @param array $item
     * @param null $blankDefault
     * @return array
     */
    public static function valueStringParse($item, $blankDefault = null)
    {
        if (is_array($item)) {
            foreach ($item as &$childItem) {
                $childItem = self::valueStringParse($childItem, $blankDefault);
            }
        } elseif (is_numeric($item)) {
            $item = $item + 0;
        } elseif (is_string($item)) {
            if (strtolower($item) === 'true') {
                $item = true;
            } elseif (strtolower($item) === 'false') {
                $item = false;
            } elseif ($item === '') {
                $item = $blankDefault;
            }
        }

        return $item;
    }

    /**
     * Rename keys of flatten.
     *
     * @param array $flatten
     * @param array $mapName EX: ['old_key' => 'new_key', ...]
     * @return array
     */
    public static function flattenRenameKeys(array $flatten, $mapName)
    {
        foreach ($flatten as $key => $row) {
            $flatten[$key] = self::renameKeys($row, $mapName);
        }

        return $flatten;
    }

    /**
     * Array group by key
     *
     * @param string $key
     * @param array $data
     * @return array
     */
    public static function groupBy($key, array $data)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }
}
