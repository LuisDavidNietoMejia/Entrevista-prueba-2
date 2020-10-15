<?php namespace App\Http\Library;

use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Library\Result;

class ResponseJson
{
    private static $successStatus = 200;
    private static $unauthorized = 401;
    private static $successStatusCreated = 201;
    private static $errorStatusCliente = 400;
    private static $errorNotfound = 404;
    private static $dangerStatusServer = 500;
    private static $forbidden = 403;
    //para request personalizados:

    public static function forbidden($message = "")
    {
        $result = Result::error($message, self::$forbidden);
        return response()->json($result, self::$forbidden);
    }

    public static function errorRequest($message)
    {
        $result = Result::error($message, self::$errorStatusCliente);
        throw new HttpResponseException(response()->json($result, self::$errorStatusCliente));
    }

    public static function notFound($message)
    {
        $result = Result::error($message, self::$errorNotfound);
        throw new HttpResponseException(response()->json($result, self::$errorNotfound));
    }

    public static function error($message = "")
    {
        $result = Result::error($message, self::$errorStatusCliente);
        return response()->json($result, self::$errorStatusCliente);
    }

    public static function success($message = "", $result = null)
    {
        if ($result!==null) {
            $result = Result::success($message, self::$successStatus, $result);
        } else {
            $result = Result::success($message, self::$successStatus);
        }
        return response()->json($result, self::$successStatus);
    }
    public static function successCreated($message = "", $result = null)
    {
        if ($result!==null) {
            $result = Result::success($message, self::$successStatusCreated, $result);
        } else {
            $result = Result::success($message, self::$successStatusCreated);
        }
        return response()->json($result, self::$successStatusCreated);
    }
    public static function danger($message = "")
    {
        $result = Result::error($message, self::$dangerStatusServer);
        return response()->json($result, self::$dangerStatusServer);
    }
    public static function unauthorised($message = "")
    {
        $result = Result::error($message, self::$unauthorized);
        return response()->json($result, self::$unauthorized);
    }
}
