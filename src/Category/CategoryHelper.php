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
use Cool\Common\Form\FormHelper;

class CategoryHelper
{
    public static function planeToTree($data, $excludeId = 0, $idKey = 'id', $pidKey = 'pid', $childrenKey = 'children')
    {
        $tree = [];

        $excludeId = intval($excludeId);
        if($excludeId) {
            foreach ($data as $item) {
                if($excludeId != $item[$idKey]) {
                    $tree[$item[$idKey]] = $item;
                    $tree[$item[$idKey]][$childrenKey] = [];
                }
            }
        } else {
            foreach ($data as $item) {
                $tree[$item[$idKey]] = $item;
                $tree[$item[$idKey]][$childrenKey] = [];
            }
        }

        foreach ($tree as $key => $item) {
            if ($item[$pidKey] != 0 && isset($tree[$item[$pidKey]])) {
                $tree[$item[$pidKey]][$childrenKey][] = &$tree[$key];
                if (empty($tree[$key][$childrenKey])) {
                    unset($tree[$key][$childrenKey]);
                }
            }
        }

        foreach ($tree as $key => $item) {
            if ($item[$pidKey] != 0) {
                unset($tree[$key]);
            }
        }

        return $tree;
    }

    public static function treeToPlaneWithLevel($tree, $level = 0, $levelKey = 'categoryLevel', $childrenKey = 'children')
    {
        $data = [];
        $levelFlag = ($level === false || empty($levelKey)) ? false : true;
        $level = intval($level);
        if($level < 0) {
            $levelFlag = false;
        }
        if($levelFlag) {
            $level++;
        }

        foreach ($tree as $key => $item) {
            if($levelFlag) {
                $item[$levelKey] = $level;
            }

            if ( ! empty($item[$childrenKey])) {
                $tmp = $item[$childrenKey];
                unset($item[$childrenKey]);
                $data[] = $item;
                $data = array_merge($data, self::treeToPlaneWithLevel($tmp, $level, $levelKey, $childrenKey));
            } else {
                $data[] = $item;
            }
        }

        return $data;
    }

    public static function treeToPlane($tree, $childrenKey = 'children')
    {
        $data = [];
        foreach ($tree as $key => $item) {
            if ( ! empty($item[$childrenKey])) {
                $tmp = $item[$childrenKey];
                unset($item[$childrenKey]);
                $data[] = $item;
                $data = array_merge($data, self::treeToPlane($tmp, $childrenKey));
            } else {
                $data[] = $item;
            }
        }

        return $data;
    }

    public static function sort($data, $renewIndex = false, $idKey = 'id')
    {
        $return = [];
        foreach ($data as $item) {
            $return[$item[$idKey]] = $item;
        }

        if($renewIndex) {
            sort($return);
        } else {
            asort($return);
        }

        return $return;
    }

    public static function render(
        $data,
        $excludeId = 0,
        $selectedId = 0,
        $indentKey = 'name',
        $indentString = '-',
        $idKey = 'id',
        $pidKey = 'pid',
        $childrenKey = 'children',
        $levelKey = 'categoryLevel',
        $level = 0
    ) {
        if(empty($data))
        {
            return '';
        }

        $data = self::planeToTree($data, $excludeId, $idKey, $pidKey, $childrenKey);
        $data =  self::treeToPlaneWithLevel($data, $level, $levelKey, $childrenKey);
        $data =  self::formatIndentKey($data, $indentKey, $indentString, $levelKey);
        $data = array_convert_to_key_value($data, $idKey, $indentKey);
        return FormHelper::makeSimpleOptionHtml($data, $selectedId);
    }


    /**
     * Indent category name
     *
     * @param array $data plane category data
     * @param string $indentKey
     * @param string $indentString
     * @param string $levelKey
     * @return array
     */
    public static function formatIndentKey($data = [], $indentKey = 'name', $indentString = '-', $levelKey = 'categoryLevel')
    {
        foreach ($data as $k => $v) {
            if($v[$levelKey] > 1) {
                $v[$indentKey] = str_repeat($indentString, $v[$levelKey] -1) . $v[$indentKey];
                $data[$k] = $v;
            }
        }

        return $data;
    }

}