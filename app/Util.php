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
}