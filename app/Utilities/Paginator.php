<?php

namespace App\Utilities;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class Paginator
{
    /**
     * Create list page to show.
     *
     * @param int $currentPage
     * @param int $pages
     * @return array
     */
    public static function getRoundPagePaginator($currentPage, $pages)
    {
        $roundOfPage = [];
        for ($i = $currentPage - 2; $i <= $currentPage + 2; $i++) {
            if (!isset($pages[$i]) and $currentPage - 2 == $i) {
                $roundOfPage[] = $currentPage + 3;
                continue;
            }
            if (!isset($pages[$i]) and $currentPage - 1 == $i) {
                $roundOfPage[] = $currentPage + 4;
                continue;
            }
            if (!isset($pages[$i]) and $currentPage + 1 == $i) {
                $roundOfPage[] = $currentPage - 4;
                continue;
            }
            if (!isset($pages[$i]) and $currentPage + 2 == $i) {
                $roundOfPage[] = $currentPage - 3;
                continue;
            }

            if (isset($pages[$i])) {
                $roundOfPage[] = $i;
            }
        }
        return $roundOfPage;
    }

    /**
     * Get paginator
     *
     * @param Collection $items
     * @param int $total
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public static function make($items, $total, $perPage)
    {
        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            LengthAwarePaginator::resolveCurrentPage(),
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
            ]
        );
    }
}
