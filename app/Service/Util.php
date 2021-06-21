<?php


namespace App\Service;


class Util
{
    public static function buildResponse($code, $message, $data,$statusCode = 200){
        return response()->json(
            [
                "code" => $code,
                "message" => $message,
                "data" => $data
            ],$statusCode
        );
    }
}
