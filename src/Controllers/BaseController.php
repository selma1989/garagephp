<?php
namespce App\Controllers;
use App\Security\Validator;
use App\Utils\Response;


abstract class BaseController{

    protected Response $response;
    protected Validator $validator;

    public function __construct(){
        $this->response = new Response();
        $this->validator = new Validator();
    }

    protected function render(string $view, array $data=[]):void{

        $viewPath = __DIR__. '/views/' $view . '.php';
        if(!file_exists($viewPath)){
            $this->response->error("Vue non touvée : $viewPath", 500);
            return;
        }
        extract($data);
        db_start();
        include $viewPath;
        $content = ob_get_clean();
        include __DIR__.'/view/layout.php';
    }
}