<?php

namespace App\Html;

use App\Repositories\CountyRepository;
class Request
{
    static function handle()
    {
        switch ($_SERVER["REQUEST_METHOD"]){
            case "GET":
                self::getRequest();
                break;
        }
    }
    private static function getRequest()
    {
        $uri = $_SERVER['REQUEST_URI'];
        switch ($uri){
            case '/counties':
                $repository = new CountyRepository();
                $entities = $repository->getAll();
                $code = 200;
                if (empty($entities)) {
                    $code = 404;
                }
                Response::response($entities, $code);
                break;
            default:
                Response::response([], 404, "$uri not found");
        }
    }
}
?>