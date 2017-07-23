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

namespace Cool\Common\Category;

class CategoryHelper
{
    public static function planeToTree($data, $excludeId = 0, $id = 'id', $pid = 'pid', $childrenKey = 'children')
    {
        $tree = [];

        $excludeId = intval($excludeId);
        if($excludeId) {
            foreach ($data as $item) {
                if($excludeId != $item[$id]) {
                    $tree[$item[$id]] = $item;
                    $tree[$item[$id]][$childrenKey] = [];
                }
            }
        } else {
            foreach ($data as $item) {
                $tree[$item[$id]] = $item;
                $tree[$item[$id]][$childrenKey] = [];
            }
        }

        foreach ($tree as $key => $item) {
            if ($item[$pid] != 0 && isset($tree[$item[$pid]])) {
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

    public static function treeToPlaneWithLevel($tree, $level = 0, $levelKey = 'categoryLevel', $id = 'id', $pid = 'pid', $childrenKey = 'children')
    {
        $data = [];
        $levelFlag = ($level === false || empty($levelKey)) ? false : true;
        $level = intval($level);
        if($level < 0) {
            return $data;
        }
        $level++;

        foreach ($tree as $key => $item) {
            if($levelFlag) {
                $item[$levelKey] = $level;
            }

            if ( ! empty($item[$childrenKey])) {
                $tmp = $item[$childrenKey];
                unset($item[$childrenKey]);
                $data[] = $item;
                $data = array_merge($data, self::treeToPlaneWithLevel($tmp, $level, $levelKey, $id, $pid, $childrenKey));
            } else {
                $data[] = $item;
            }
        }

        return $data;
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

    public static function render(
        $tree,
        $template,
        $keys = [],
        $indentKey = 'name',
        $indentString = '',
        $id = 'id',
        $pid = 'pid',
        $childrenKey = 'children',
        $level = 0
    ) {
        if(empty($tree) || empty($template) || empty($keys))
        {
            return '';
        }

        $html = '';
        foreach ($tree as $item) {
            $tmp = [];
            foreach ($keys as $k) {
                if($k == $indentKey) {
                    $tmp[] = str_repeat($indentString, $level) . $item[$k];
                } else {
                    $tmp[] = $item[$k];
                }

            }

            $html .= vsprintf($template, $tmp);
            if(!empty($item[$childrenKey])) {
                $subLevel = $level + 1;
                 $html .= self::render($item[$childrenKey], $template, $keys, $indentKey, $indentString, $id, $pid, $childrenKey, $subLevel);
            }
        }

        return $html;
    }
}