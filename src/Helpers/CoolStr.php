<?php
namespace Cool\Common;

/**
 * Class CoolStr
 *
 * @package Cool\Common
 */
class CoolStr
{
    /**
     *  generate guid
     */
    public static function guid()
    {
        //if (function_exists('com_create_guid') === true)
        //{
        //	return trim(com_create_guid(), '{}');
        //}
        //return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));

        // 1. delete '-', total long size changed from 36 to 32
        // 2.letters changed from upper case to lower case
        if (function_exists('com_create_guid') === true)
        {
            return strtolower(str_replace('-', '', trim(com_create_guid(), '{}')));
        }

        return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * convert string to camel case
     *
     * @param $str
     * @return mixed
     */
    public static function camelize($str)
    {
        return str_replace(' ', '', lcfirst(ucwords(str_replace('_', ' ', $str))));
    }

    /**
     * convert string to underscore case
     *
     * @param $str
     * @return string
     */
    public static function underscore($str)
    {
        return strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $str));
    }


    /**
     * Generate name by microtime
     *
     * @param string $extension
     * @return string
     */
    public static function timeName($extension = '', $dataFormat = 'Ymd-His')
    {
        $time = explode(' ', microtime());
        $time = round($time[0], 4) * 10000;
        $prefix = empty($dataFormat) ? '' : date($dataFormat) . '-';
        $time = $prefix . vsprintf('%04d', $time);

        if($extension === '' || $extension === false || $extension === null) {
            return $time;
        }

        $extension = '.' . ltrim($extension, '.');

        return $time . $extension;
    }

    /**
     * Generate name by guid
     *
     * @param string $extension
     * @return string
     */
    public static function guidName($extension = '')
    {
        $guid = self::guid();

        if($extension === '' || $extension === false || $extension === null) {
            return $guid;
        }

        $extension = '.' . ltrim($extension, '.');

        return $guid . $extension;
    }

    /**
     * Convert array to array code string
     *
     * @param  string  $json
     * @return mixed|string
     */
    public static function jsonToArrayCode($json = '')
    {
        $data = json_decode($json, true);
        if(is_string($data)) {
            return '\'' . $data . '\'';
        } elseif(is_null($data)) {
            return 'null';
        } elseif(!is_array($data)) {
            return $data;
        }
        $code = str_replace('\'', '\\\'', $json);
        $code = str_replace('"', '\'', $code);
        $code = str_replace('{', '[', $code);
        $code = str_replace('}', ']', $code);
        $code = str_replace(':', '=>', $code);

        return $code;
    }
}
