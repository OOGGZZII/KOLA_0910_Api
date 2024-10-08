<?php
 
namespace App\Html;
 
use App\Repositories\CountyRepository;
use App\Repositories\CityRepository;
class Request
{
    static function handle()
    {
        switch ($_SERVER["REQUEST_METHOD"]) {
            case "POST":
                self::postRequest();
                break;
            case "GET":
                self::getRequest();
                break;
            case "PUT":
                self::putRequest();
                break;
            case "DELETE":
                self::deleteRequest();
                break;
            default:
                echo 'Unknown request type';
                break;
        }
    }
 
    public static function getRequest()
    {
        // $uri = $_SERVER['REQUEST_URI'];
 
        $uri = self::getResourceName();
        switch ($uri) {
            case 'counties':
                $id = self::getResourceId();
                $repository = new CountyRepository();
                if ($id) {
                    $entities = $repository->find($id);
                } else {
                    $entities = $repository->getAll();
                }
 
                $code = 200;
                if (empty($entities)) {
                    $code = 404;
                }
                Response::response($entities, $code);
                break;
            case 'cities':
                $id = self::getResourceId();
                $repository = new CityRepository();
                if ($id) {
                    $entities = $repository->find($id);
                } else {
                    $countyId = self::getCountyId();
                    $entities = $repository->getAllCities($countyId);
                }
 
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
 
    private static function deleteRequest()
    {
        $id = self::getResourceId();
        if (!$id) {
            Response::response([], 400, Response::STATUSES[400]);
        }
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $code = 404;
                $db = new CountyRepository();
                $result = $db->delete($id);
                if ($result) {
 
                    Response::response([], 200);
                } else {
                    Response::response([], 404);
                }
        }
        return;
    }
    private static function postRequest()
    {
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $data = self::getRequestData();
                if (isset($data['name'])) {
                    $db = new CountyRepository();
                    $newId = $db->post($data);
                    $code = 201;
                    if (!$newId) {
                        $code = 400;
                    }
                }
        }

    }
    private static function putRequest()
    {
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $id = self::getResourceId();
                $data = self::getRequestData();
                if (isset($data['name'])) {
                    $db = new CountyRepository();
                    $newId = $db->put($data, $id);
                    $code = 201;
                    if (!$newId) {
                        $code = 400;
                    }
                }
        }
    }
 
 
    //szétdarabolja és ha az utolsó szám akkor az utolsó előttit adja vissza
    private static function getResourceName()
    {
        $arrUri = explode("/", $_SERVER['REQUEST_URI']);
        $last = $arrUri[count($arrUri) - 1]; //ha egy szam a vege akkor az egy id es elotte erroforras
        if (is_numeric($last)) {
            $last = $arrUri[count($arrUri) - 2];
        }
        return $last;
    }
    private static function getCountyId()
    {
        $arrUri = explode("/", $_SERVER['REQUEST_URI']);
        $last = $arrUri[count($arrUri) - 2];
        return $last;
    }
    private static function getResourceId()
    {
        // $arrUri = self::getArrUri($_SERVER('REQUEST.URI'));
        $arrUri = explode("/", $_SERVER['REQUEST_URI']);
        $last = $arrUri[count($arrUri) - 1];
        if (is_numeric($last)) {
            return $last;
        }
        return false;
    }
    
    private static function getRequestData():?array
    {
        return json_decode(file_get_contents("php://input"), true);
    }
 
 
}
