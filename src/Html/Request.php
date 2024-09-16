<?php

namespace App\Html;

use App\Repositories\CountyRepository;
use App\Repositories\CityRepository;
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
        $uriT = explode('/', $uri);
        switch (count($uriT, 1)){
            case 2:
                $repository = new CountyRepository();
                $entities = $repository->getAll();
                $code = 200;
                if (empty($entities)) {
                    $code = 404;
                }
                Response::response($entities, $code);
                break;
            case 3:
                //counties/index
                break;
            case 4:
                $repository = new CityRepository();
                $entities = $repository->getAll();
                $code = 200;
                if (empty($entities)) {
                    $code = 404;
                }
                Response::response($entities, $code);
                break;
            case 5:
                //counties/i/cities/i
                break;
            default:
                Response::response([], 404, "$uri not found");
        }
    }
}
?>