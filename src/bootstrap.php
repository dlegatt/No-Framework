<?php declare(strict_types = 1);

namespace Legattd;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use League\Route\RouteCollection;
use Zend\Diactoros\Response\SapiEmitter;

require_once __DIR__ . '/../vendor/autoload.php';
error_reporting(E_ALL);

$env = 'development';

$whoops = new \Whoops\Run;
if ($env !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function($e) {
        echo 'Todo: friendly error page and send email to developer';
    });
}
$whoops->register();

$psr7Factory = new DiactorosFactory();
/** @var ServerRequestInterface $request */
$request = $psr7Factory->createRequest(Request::createFromGlobals());
/** @var ResponseInterface $response */
$response = $psr7Factory->createResponse(new Response());
$route = new RouteCollection();
$route->map('GET', '/', function() use ($request,$response) {
    $response->getBody()->write('<h1>Hello World!</h1>');
    return $response;
});
$route->map('GET', '/route-2', function() use ($request,$response) {
    $response->getBody()->write('<h1>This is also a route</h1>');
    return $response;
});

$response = $route->dispatch($request,$response);
$emitter = new SapiEmitter();
$emitter->emit($response);



