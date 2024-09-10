<?php

namespace App\Html;

class Response
{
    const STATUSES = [
        200 => "OK",
        201 => "Created",
        202 => "Accepted",
        204 => "No Content",
        400 => "Bad Request",
        401 => "Unathourized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
    ];
    public function __call($name, $arguments)
    {
        $this->response(['data'=>[]], 404);
    }
    static function response(array $data, $code = 200, $message = '')
    {
        if(isset(self::STATUSES[$code])){
            http_response_code($code);
            if (!$message) {
                $message = self::STATUSES[$code];
            }
            $protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.0';
            header($protocol . ' ' . $code . ' ' . self::STATUSES[$code]);
        }
        header('Content-Type: application/json');
        $response = [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];

        echo json_encode($response, JSON_THROW_ON_ERROR);
    }
}
?>