<?php
namespace Cool\Common;

/**
 * Class CoolHelpers
 *
 * @package Cool\Common
 */
class CoolHelpers
{
    /**
     * write log to file
     *
     * @param string $data
     * @param string $file
     * @param string $type
     * @return bool
     */
    public static function fileWriteLog($data = '', $file = '', $type = 'a')
    {
        if (empty($file)) {
            return false;
        }
        $data = "--------------------------------------------------------------------------------\n" .
            date('Y-m-d H:i:s') . "\n" . print_r($data, true) . "\n";

        return self::fileWrite($file, $data, $type);
    }


    /**
     * write to file
     *
     *@param string $file
     * @param string $data
     * @param string $mode
     * @param int $maxRetries
     * @param int $minUsleep
     * @param int $maxUsleep
     * @return bool
     */
    public static function fileWrite(
        $file = '',
        $data = '',
        $mode = 'w',
        $maxRetries = 20,
        $minUsleep = 100,
        $maxUsleep = 1000
    ) {
        $fp = fopen($file, $mode);
        if (!$fp) {
            return false;
        }
        $retries = 0;
        do {
            if ($retries > 0) {
                usleep(mt_rand($minUsleep, $maxUsleep));
            }
            $retries += 1;
        } while (!flock($fp, LOCK_EX) and $retries <= $maxRetries);
        if ($retries == $maxRetries) {
            return false;
        }
        fwrite($fp, $data);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        return true;
    }


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
     * Convert array to one-dimensional array
     *
     * @param $data
     * @param string $keyName
     * @param string $valueName
     * @return array|bool
     */
    public static function arrayToKv($data, $keyName = 'id', $valueName = 'name')
    {
        if (empty($data))
        {
            return FALSE;
        }
        $result = [];
        foreach ($data as $v)
        {
            $result[$v[$keyName]] = $v[$valueName];
        }

        return $result;
    }


    /**
     * Convert array to one-dimensional array
     *
     * @param $data
     * @param string $valueName
     * @return array|bool
     */
    public static function arrayToV($data, $valueName = 'name')
    {
        if (empty($data))
        {
            return FALSE;
        }
        $result = [];
        foreach ($data as $v)
        {
            $result[] = $v[$valueName];
        }

        return $result;
    }


    /**
     * Index array by indexKey
     *
     * @param array $array
     * @param string $indexKey
     * @return array
     */
    public static function arrayIndex($array = [], $indexKey = 'key')
    {
        $result = [];
        foreach ($array as $value) {
            if(isset($value[$indexKey])) {
                $result[$value[$indexKey]] = $value;
            }
        }

        return $result;
    }


    public static function nameFromTime($extension = '', $dataFormat = 'Ymd-His')
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


    public static function guidName($extension = '')
    {
        $guid = self::guid();

        if($extension === '' || $extension === false || $extension === null) {
            return $guid;
        }

        $extension = '.' . ltrim($extension, '.');

        return $guid . $extension;
    }


    public static function resultArray($errCode = 0, $msg = '', $data = [])
    {
        return [
            'errCode' => $errCode,
            'msg' => $msg,
            'data' => $data
        ];
    }


    public static function humanFileSize($bytes, $decimals = 2)
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) .@$size[$factor];
    }


    /**
     * Convert array to code string
     *
     * @param  array  $data
     * @param  int  $indent
     * @param  string  $repeatStr
     * @return array|string
     */
    public static function arrayToCode($data = [], $indent = 0, $repeatStr = '    ')
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
                $str .= sprintf($indentStr . '%s => %s,' . PHP_EOL, $k, self::arrayToCode($v, $indent, $repeatStr));
            } else {
                $str .= sprintf($indentStr . '\'%s\' => %s,' . PHP_EOL, $k, self::arrayToCode($v, $indent, $repeatStr));
            }
        }

        $str .= str_repeat($repeatStr, $indent - 1) . ']';

        return $str;
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
