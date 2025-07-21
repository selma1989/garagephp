<?php
// Affiche les erreurs directement dans la page
inti_set('display_errors', 1);
error_reporting(E_ALL);

//Inclure l'autoloader
require_once __DIR__ . '/vendor/autoload.php';

//import des classes
use App\Config\Config;
use App\Utils\Response;

//Démarrer une session ou repend la session existante
session_start();

//Charger nos variables d'environnemnt
Config::load();

//Définir des routes avec la bibliothèque FastRoute
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r){


    $r->addRoute('GET','/',[App\Controllers\HomeController::class, 'index']);
    $r->addRoute('GET','/login',[App\Controllers\AuthController::class, 'showlogin']);
    $r->addRoute('POST', '/login',[App\Controllers\AuthController::class, 'login']);
    $r->addRoute('POST','/logout',[App\Controllers\AuthController::class, 'logout']);
    $r->addRoute('GET','/cars',[App\Controllers\CarController::class, 'index']);
});

//Traitement de la requéte

//récupérer la methode HTTP (GET, POST,PUT,PATCH) et l'URI(login, /car/I)
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

//Dispatcher FastRoute
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
$response = new Response();

//Analyser le resultat du dispatching
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        $response->error("404 - Page non trouvée", 404);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response->error("405 - Méthode non autorisée", 405);    
        break;
    case FastRoute\Dispatcher::FOUND:
        [$controllerClass, $method] = $routeInfo[1];
        $vars = $routeInfo[2];
        try{
            $controller = new $controllerClass();
            call_user_func_array([$controller, $method], $vars);  
        }catch(\Exception $e){
            if(Config::get('APP_DEBUG') === 'true'){
                $response->error("Erreur 500 : " . $e->getMessage(). " dans " .$e->getFile() . ":" .$e->getLine(), 500);    
            }else{
                (new \App\Utils\Logger())->log('ERROR', 'Erreur Serveur :' . $e->getMessage());
                $response->error("Une erreurinterne est survenue .", 500);
            }
        }    
        break; 
}