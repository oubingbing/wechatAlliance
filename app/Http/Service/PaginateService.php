<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/17
 * Time: 下午5:02
 */

namespace App\Http\Service;


class PaginateService
{
    /**
     * 封装一下分页函数
     *
     * @author yezi
     *
     * @param $query
     * @param $pageParams
     * @param null $columns
     * @param null $map
     * @return array
     */
    public function paginate($query, $pageParams, $columns = null, $map = null)
    {
        if ($columns === null || !is_array($columns)) {
            $columns = ['*'];
        }

        $perPage     = $pageParams['page_size'] ? $pageParams['page_size'] : 10;
        $currentPage = $pageParams['page_number'] ? $pageParams['page_number'] : 1;

        $result      = $query->paginate($perPage, $columns, null, $currentPage);
        $items       = $result->getCollection();
        if ($map != null) {
            $items = $items->map($map);
        }

        $result = $this->paginateFormat($result->perPage(), $result->currentPage(), $result->lastPage(), $result->total(), $items);

        return $result;
    }

    /**
     * 格式化分页返回数据
     *
     * @param $perPage
     * @param $currentPage
     * @param $lastPage
     * @param $total
     * @param $items
     * @return array
     */
    public function paginateFormat($perPage, $currentPage, $lastPage, $total, $items)
    {
        return [
            'page'      => [
                'size'        => $perPage,
                'number'      => $currentPage,
                'total-pages' => $lastPage,
                'total-items' => $total,
                'total_items' => $total,
            ],
            'page_data' => $items
        ];
    }

}