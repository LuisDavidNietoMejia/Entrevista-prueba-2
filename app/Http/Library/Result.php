<?php

namespace App\Http\Library;

class Result
{
    private static $result = array("success" => false, "code" => 0, "message" => "");

    public static function error($message = "", $code = 0)
    {
        self::$result["message"]=$message;
        self::$result["code"]=$code;
        return self::$result;
    }

    public static function success($message = "", $code = 1, $result = null)
    {
        self::$result["message"]= $message;
        self::$result["code"]= $code;
        self::$result["success"] = true;
        self::$result["result"] = $result;
        return self::$result;
    }
}
