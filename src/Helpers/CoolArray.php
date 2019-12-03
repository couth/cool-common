<?php
namespace Cool\Common;

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

    public static function result($code = 0, $msg = '', $data = [])
    {
        return [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
    }
}
