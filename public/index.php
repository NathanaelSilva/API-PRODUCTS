<?php
use Slim\Factory\AppFactory;
use App\Controllers\ProdutoController;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$scriptName = $_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '/index.php';
$basePath = str_replace('\\', '/', dirname($scriptName));
$basePath = str_replace('/public', '', $basePath);
$basePath = $basePath === '/' ? '' : $basePath;
$app->setBasePath($basePath);

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->get('/api/produtos', [ProdutoController::class, 'listar']); 
$app->get('/api/produtos/{id}',[ProdutoController::class, 'buscar']);
$app->post('/api/produtos', [ProdutoController::class, 'criar']);
$app->put('/api/produtos/{id}', [ProdutoController::class, 'atualizar']);
$app->delete('/api/produtos/{id}', [ProdutoController::class, 'deletar']);

$app->run();