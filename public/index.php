<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Framework\Middleware\AuthorizationMiddleware;
use Framework\Middleware\DatabaseMiddleware;
use Framework\Middleware\SessionMiddleware;
use Framework\Middleware\OptionMiddleware;
use Framework\Middleware\UserAgentMiddleware;
use Silex\Application;
use Stack\Builder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Framework\Provider\LoggerServiceProvider;

date_default_timezone_set('UTC');
$app = new Silex\Application();

// Loading environment file
// This environment file will be updated manually, and in git ignore list on dev/staging/production
require __DIR__ . '/../config/env.php';

// Request filter
require __DIR__ . '/../config/request.php';

// Loading common config
require __DIR__ . '/../config/common.php';

// Loading config files based on enviroment
$configPath = __DIR__ . '/../config/' . $app['env'];
require $configPath . '/config.php';
require $configPath . '/database.php';
require $configPath . '/jwt.php';
// register services
require __DIR__ . '/../config/service.php';
$app->before(function (Request $request) {
   if ($request->getMethod() == 'OPTIONS') {
       $response = new JsonResponse();
       $response->headers->set("Access-Control-Allow-Origin","*");
       $response->headers->set("Access-Control-Allow-Methods","GET,POST,PUT,DELETE,OPTIONS");
       $response->headers->set("Access-Control-Allow-Headers","Content-Type,X-Token,X-Access-Token");
       $response->setStatusCode(200);
       return $response->send();
   }
}, Application::EARLY_EVENT);
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});
$app->after(function (Request $request, JsonResponse $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set("Access-Control-Allow-Methods","GET,POST,PUT,DELETE,OPTIONS");
});
// extend Silex default route config, using Yaml RouteCollection from Symfony
$app['routes'] = $app->extend('routes', function (RouteCollection $routes, Application $app) {
    $loader     = new YamlFileLoader(new FileLocator(__DIR__ . '/../config'));
    $collection = $loader->load('routes.yml');
    $routes->addCollection($collection);
    return $routes;
});
$stack = (new Stack\Builder())
    ->push(OptionMiddleware::class, $app)
    ->push(DatabaseMiddleware::class, $app)
    ->push(SessionMiddleware::class, $app)
    ->push(AuthorizationMiddleware::class, $app);

$appStack = $stack->resolve($app);
$request  = Request::createFromGlobals();
$response = $appStack->handle($request);
$response->send();
$appStack->terminate($request, $response);
