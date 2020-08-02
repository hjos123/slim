<?php
/*
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Allow: GET, POST, PUT, DELETE");
*/
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';

$config = ['settings' => [
    'secret' => base64_encode("@0¿9.2*53_Lom,io-No=EstuY/OpUt!+aMaDRE$"),
]];
$app = new \Slim\App($config);

/*Ruta Middleware*/
require '../src/middleware.php';

/*Ruta Autorizacion*/
require '../src/rutas/auth.php';

$app->group('/api', function(\Slim\App $app) {
  /* Ruta usuarios */
  require '../src/rutas/usuarios.php';
  /* Ruta usuarios */
  require '../src/rutas/requisitos.php';
});

$app->run();
