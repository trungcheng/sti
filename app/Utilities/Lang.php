<?php

namespace App\Utilities;

/**
 * Class Lang
 * @package App\Utilities
 */
class Lang
{
    /**
     * get list pagination page size options
     * @return array
     */
    public static function getPaginationOptions()
    {
        $suffix = __('labels.paginator_unit');
        return array_map(
            function ($option) use ($suffix) {
                return $option . $suffix;
            },
            config("main.pagination.options")
        );
    }
}
