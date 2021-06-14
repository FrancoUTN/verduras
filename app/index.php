<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Illuminate\Database\Capsule\Manager as Capsule;


require __DIR__ . '/../vendor/autoload.php';

require_once './controllers/UsuarioController.php';

require_once './middlewares/Verificadora.php';
require_once './controllers/HortalizaController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// Eloquent
$container=$app->getContainer();

$capsule = new Capsule;
// $capsule->addConnection([
//     'driver'    => 'mysql',
//     'host'      => $_ENV['MYSQL_HOST'],
//     'database'  => $_ENV['MYSQL_DB'],
//     'username'  => $_ENV['MYSQL_USER'],
//     'password'  => $_ENV['MYSQL_PASS'],
//     'charset'   => 'utf8',
//     'collation' => 'utf8_unicode_ci',
//     'prefix'    => '',
// ]);
$capsule->addConnection([
    'driver'    => 'mysql',

    'host'      => 'localhost',
    'database'  => 'simulacro',
    'username'  => 'root',
    'password'  => '',
    
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Routes
$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("Slim Framework 4 PHP");
    return $response;
});

$app->group('/login', function (RouteCollectorProxy $group) {
    $group->post('[/]', \Verificadora::class . ':Verificar');
});

$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
    $group->put('/{id}', \UsuarioController::class . ':ModificarUno');
    $group->delete('/{id}', \UsuarioController::class . ':BorrarUno');
});

$app->group('/hortalizas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \HortalizaController::class . ':TraerTodos');
    $group->get('/{hortaliza}', \HortalizaController::class . ':TraerUno');
    $group->post('[/]', \HortalizaController::class . ':CargarUno');
    $group->put('/{id}', \HortalizaController::class . ':ModificarUno');
    $group->delete('/{id}', \HortalizaController::class . ':BorrarUno');
});


$app->run();
