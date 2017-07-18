<?php

/**
 * Author: Carl Liu<coolboy@outlook.com>
 * Complete Date: 2017/07/18 19:58
 * Change record:
 *   Date:
 *   Version:
 *   Author:
 *   Content:
 */

namespace Cool\Common\Catagory;

class CatagoryHelper
{
    public static function planeToTree($data, $id = 'id', $pid = 'pid', $childrenKey = 'children')
    {
        $tree = [];
        foreach ($data as $item) {
            $tree[$item[$id]] = $item;
            $tree[$item[$id]][$childrenKey] = [];
        }
        foreach ($tree as $key => $item) {
            if ($item[$pid] != 0) {
                $tree[$item[$pid]][$childrenKey][] = &$tree[$key];
                if (empty($tree[$key][$childrenKey])) {
                    unset($tree[$key][$childrenKey]);
                }
            }
        }
        foreach ($tree as $key => $item) {
            if ($item['pid'] != 0) {
                unset($tree[$key]);
            }
        }

        return $tree;
    }

    public static function treeToPlane($tree, $id = 'id', $pid = 'pid', $childrenKey = 'children')
    {
        $data = [];
        foreach ($tree as $key => $item) {
            if ( ! empty($item[$childrenKey])) {
                $tmp = $item[$childrenKey];
                unset($item[$childrenKey]);
                $data[] = $item;
                $data = array_merge($data, self::treeToPlane($tmp, $id, $pid, $childrenKey));
            } else {
                $data[] = $item;
            }
        }

        return $data;
    }

    public static function sort($data, $renewIndex = false, $id = 'id')
    {
        $return = [];
        foreach ($data as $item) {
            $return[$item[$id]] = $item;
        }

        if($renewIndex) {
            sort($return);
        } else {
            asort($return);
        }

        return $return;
    }
}