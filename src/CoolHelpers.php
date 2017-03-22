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
}
