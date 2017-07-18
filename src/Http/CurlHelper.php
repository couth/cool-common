<?php
namespace Cool\Common\Http;

class CurlHelper
{
    public static function curlPost($url, $data, $header = [], $isJson = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($isJson) {
            $header = array_merge($header, [
                'Accept: application/json',
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ]);
        }
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $ret = curl_exec($ch);
        curl_close($ch);

        return $ret;
    }

    public static function curlGet($url, $data, $header = [])
    {
        if ($data) {
            $join = strpos($url, '?')===false ? '?' : '&';
            $url .= $join . http_build_query($data);
        }

        die($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $ret = curl_exec($ch);
        curl_close($ch);

        return $ret;
    }
}