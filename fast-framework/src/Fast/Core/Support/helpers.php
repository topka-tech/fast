<?php
/**
 * Created by IntelliJ IDEA.
 * User: hongyang
 * Date: 2017/6/15
 * Time: 下午4:43
 */

/**
 * Generates an UUID
 *
 * @author     Anis uddin Ahmad
 * @param      string  an optional prefix
 * @return     string  the formatted uuid
 */

if (!function_exists('uuid')) {

    function uuid($prefix = '')
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid  = substr($chars,0,8) . '-';
        $uuid .= substr($chars,8,4) . '-';
        $uuid .= substr($chars,12,4) . '-';
        $uuid .= substr($chars,16,4) . '-';
        $uuid .= substr($chars,20,12);
        return $prefix . $uuid;
    }
}




if (!function_exists('debug')) {
    function debug($content){
        echo   date("Y-m-d H:i:s"). " $content\n";
    }
}



if (!function_exists('startsWith')) {
    function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
}

if (!function_exists('endsWith')) {

    function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

}
