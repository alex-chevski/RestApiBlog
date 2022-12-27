<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\utilities;

/**
 * @param mixed $page
 * @param mixed $total_rows
 * @param mixed $records_per_page
 * @param mixed $page_url
 */
function getPaging($page, $total_rows, $records_per_page, $page_url)
{
    $paging_arr = [];

    $paging_arr['first'] = $page > 1 ? "{$page_url}page=1" : '';

    $total_pages = ceil($total_rows / $records_per_page);

    $range = 2;

    $initial_num = $page - $range;

    $condition_limit_num = ($page + $range) + 1;

    $paging_arr['pages'] = [];

    $page_count = 0;

    for ($x = $initial_num; $x < $condition_limit_num; ++$x) {
        if (($x > 0) && ($x <= $total_rows)) {
            $paging_arr['pages'][$page_count]['page'] = $x;
            $paging_arr['pages'][$page_count]['url'] = "{$page_url}page={$x}";
            $paging_arr['pages'][$page_count]['current_page'] = $x == $page ? 'yes' : 'no';
            ++$page_count;
        }
    }

    $paging_arr['last'] = $page < $total_rows ? "{$page_url}page={$total_pages}" : '';

    return $paging_arr;
}
