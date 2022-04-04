<?php


namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    /**
     * @param RequestInterface $request
     * @param null $arguments
     * @return bool|mixed
     * @aliases auth
     * Girilen Token Geçerlimi değilmi kontrol et.
     */
    public function before(RequestInterface $request, $arguments = null)
    {

        $Auth = new \App\Controllers\Auth();
        $Kontrol = $Auth->isLogged();
        if(!$Kontrol["status"]){
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(401);
            echo json_encode($Kontrol);
            exit();
        }
        return true;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // TODO: Implement after() method.
    }
}