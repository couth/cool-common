<?php
namespace Cool\Common\Helpers;

/**
 * Class CoolArray
 *
 * @package Cool\Common
 */
class CoolArray
{
    /**
     * get string value safely
     *
     * @param $arr
     * @param $k
     * @return string
     */
    public static function getStr($arr, $k)
    {
        return $arr[$k] ? (string) $arr[$k] : '';
    }

    /**
     * get int value safely
     *
     * @param $arr
     * @param $k
     * @return string
     */
    public static function getInt($arr, $k)
    {
        return $arr[$k] ? (int) $arr[$k] : 0;
    }

    /**
     * get float value safely
     *
     * @param $arr
     * @param $k
     * @return string
     */
    public static function getFloat($arr, $k)
    {
        return $arr[$k] ? (float) $arr[$k] : 0;
    }

    /**
     * get object value safely
     *
     * @param $arr
     * @param $k
     * @return string
     */
    public static function getObj($arr, $k)
    {
        return $arr[$k] ? (object) $arr[$k] : null;
    }

    /**
     * get array value safely
     *
     * @param $arr
     * @param $k
     * @return string
     */
    public static function getArr($arr, $k)
    {
        return $arr[$k] ? (array) $arr[$k] : null;
    }

    /**
     * Convert array to code string
     *
     * @param  array  $data
     * @param  int  $indent
     * @param  string  $repeatStr
     * @return array|string
     */
    public static function toCode($data = [], $indent = 0, $repeatStr = '    ')
    {
        if(is_string($data)) {
            return '\'' . $data . '\'';
        } elseif(is_null($data)) {
            return 'null';
        } elseif(!is_array($data)) {
            return $data;
        }
        $str = '[' . PHP_EOL;
        $indent ++;
        foreach ($data as $k => $v) {
            $indentStr = str_repeat($repeatStr, $indent);
            if (is_numeric($k) && !is_string($k)) {
                $str .= sprintf($indentStr . '%s => %s,' . PHP_EOL, $k, self::toCode($v, $indent, $repeatStr));
            } else {
                $str .= sprintf($indentStr . '\'%s\' => %s,' . PHP_EOL, $k, self::toCode($v, $indent, $repeatStr));
            }
        }
        $str .= str_repeat($repeatStr, $indent - 1) . ']';
        return $str;
    }

    public static function retArr(array $data = [], string $msg = '', int $code = 0, $more = []): array
    {
        // make sure $data is {}
        $data = empty($data) ? new \stdClass() : $data;
        if(is_array($data) && !is_string(array_keys($data)[0])) {
            $data = [
                'data' => $data,
            ];
        }
        $ret = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];

        return array_merge($more, $ret);
    }

    public static function retJson(array $data = [], string $msg = '', int $code = 0, $more = []): string
    {
        return json_encode(self::retArr($data, $msg, $code, $more));
    }

    /**
     * Check api data structure
     * only support array|int|string|bool, not support class
     *
     * @param array $data
     * @param array $structure
     * @param bool $notCheckNumKey
     * @param bool $checkLeafType
     * @param bool $deep
     * @return bool
     */
    public static function checkStructure($data = [], $structure = [], $notCheckNumKey = true, $checkLeafType = false, $deep = true)
    {
        // type invalid
        if(!is_array($data) || !is_array($structure)) {
            return false;
        }
        if(empty($structure)) {
            return true;
        }

        if($notCheckNumKey && (array_values($structure) == $structure)) {
            return true;
        }

        foreach ($structure as $k => $v) {
            if($notCheckNumKey && is_numeric($k)) {
                continue;
            }
            if (!key_exists($k, $data)) {
                return false;
            }

            if ($deep && is_array($v) && !empty($v)) { // check v
                if (!is_array($data[$k])) {
                    return false;
                }

                if ($notCheckNumKey && (array_values($v) == $v)) {
                    continue;
                }

                if (!self::checkStructure($data[$k], $v, $notCheckNumKey, $checkLeafType, $deep)) {
                    return false;
                }
            }

            if($checkLeafType) {
                if(gettype($data[$k]) !== gettype($v)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 转换数组，将所有键由下划线式转为驼峰式
     *
     * @param array $data
     * @param  bool  $ucfirst 是否大驼峰式
     * @param  bool  $deep
     * @return array
     */
    public static function camelize(array $data = [], bool $ucfirst = false, bool $deep = false)
    {
        if(empty($data)) {
            return $data;
        }

        $ret = [];
        foreach ($data as $k => $v) {
            if (is_numeric($k)) {
                $ret[$k] = is_array($v) && $deep ? self::camelize($v, $ucfirst, $deep) : $v;
            } else {
                $ret[CoolStr::camelize($k, $ucfirst)] = is_array($v) && $deep ? self::camelize($v, $ucfirst, $deep) : $v;
            }
        }

        return $ret;
    }

    /**
     * 转换数组，将所有键由驼峰式转为下划线式
     *
     * @param array $data
     * @param  bool  $deep
     * @return array
     */
    public static function underscore(array $data = [], bool $deep = false)
    {
        if(empty($data)) {
            return $data;
        }

        $ret = [];
        foreach ($data as $k => $v) {
            if (is_numeric($k)) {
                $ret[$k] = is_array($v) && $deep ? self::underscore($v, $deep) : $v;
            } else {
                $ret[CoolStr::underscore($k)] = is_array($v) && $deep ? self::underscore($v, $deep) : $v;
            }
        }

        return $ret;
    }

    /**
     * 按给定键，过滤数组（通常用来去除掉不需要的键）
     *
     * @param array $arr
     * @param array $keys
     * @return array
     */
    public static function filterKeys($arr = [], $keys = [])
    {
        if(empty($arr) || empty($keys)) {
            return [];
        }

        return array_intersect_key($arr, array_flip($keys));
    }
}
