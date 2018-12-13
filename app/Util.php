<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/12
 * Time: 16:16
 */

namespace App;

class Util{
    public static function jsonp($data, $callback = 'callback') {
        @header('Content-Type: application/json');
        @header("Expires:-1");
        @header("Cache-Control:no-cache");
        @header("Pragma:no-cache");
        if (isset($_REQUEST[$callback])) {
            header("Access-Control-Allow-Origin:*");
            echo $_REQUEST[$callback].'('.json_encode($data, JSON_UNESCAPED_UNICODE).')';
        } else echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    //加密
    public static function up_encode($str) {
        if (is_array($str) || is_object($str)) $str = json_encode($str);
        //$cr = new Crypt3Des (Crypt3DesKey, Crypt3DesIV);
        $cr = new Aes(Crypt3DesKey);
        $str = $cr->encrypt($str);
        $str = str_replace('+', '-', $str);
        $str = str_replace('/', '.', $str);
        $str = str_replace('=', '!', $str);
        return $str;
    }

    //解密
    public static function up_decode($str) {
        if (!$str) return $str;
        $str = urldecode($str);
        $str = trim($str);
        //$cr = new Crypt3Des (Crypt3DesKey, Crypt3DesIV);
        $cr = new Aes(Crypt3DesKey);
        $str = str_replace('-', '+', $str);
        $str = str_replace('.', '/', $str);
        $str = str_replace('!', '=', $str);
        $str = $cr->decrypt($str);
        $couldbeA = json_decode($str, true);
        if (is_array($couldbeA)) return $couldbeA;
        return false;
    }
}