<?php
namespace Cool\Common\Http;

class CurlHelper {
    /**
     * @param $url
     * @param $data
     * @param array $header
     * @param bool $cookieType FALSE | 'CURLOPT_COOKIEJAR' | 'CURLOPT_COOKIEFILE'
     * @param string $cookieFile
     * @return mixed
     */
    public static function curlPost(
        $url,
        $data,
        $header = [],
        $cookieType = FALSE,
        $cookieFile = 'cookie_temp.txt'
    ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        if ($cookieType == 'CURLOPT_COOKIEFILE')
        {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        } elseif ($cookieType == 'CURLOPT_COOKIEJAR')
        {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        if ($header)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $ret = curl_exec($ch);
        curl_close($ch);

        return $ret;
    }


    /**
     * @param $url
     * @param $data
     * @param array $header
     * @param bool $cookieType FALSE | 'CURLOPT_COOKIEJAR' | 'CURLOPT_COOKIEFILE'
     * @param string $cookieFile
     * @return mixed
     */
    public static function curlGet(
        $url,
        $data,
        $header = [],
        $cookieType = FALSE,
        $cookieFile = 'cookie_temp.txt'
    ) {
        if ($data)
        {
            $join = strpos($url, '?') === FALSE ? '?' : '&';
            $url .= $join . http_build_query($data);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        if ($cookieType == 'CURLOPT_COOKIEFILE')
        {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        } elseif ($cookieType == 'CURLOPT_COOKIEJAR')
        {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($header)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $ret = curl_exec($ch);
        curl_close($ch);

        return $ret;
    }


    public static function curlGetJson(
        $url,
        $data,
        $header = array('Content-Type: application/json;charset=utf-8')
    ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($header)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $ret = curl_exec($ch);
        curl_close($ch);

        return $ret;
    }
}