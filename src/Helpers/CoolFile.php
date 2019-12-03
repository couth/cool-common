<?php
namespace Cool\Common;

/**
 * Class CoolFile
 *
 * @package Cool\Common
 */
class CoolFile
{
    /**
     * write log to file
     *
     * @param string $data
     * @param string $file
     * @param string $type
     * @return bool
     */
    public static function writeLog($data = '', $file = '', $type = 'a')
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
}
