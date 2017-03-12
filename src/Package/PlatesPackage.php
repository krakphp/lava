<?php

namespace Krak\Lava\Package;

use Krak\Lava;
use Krak\Cargo;

class PlatesPackage extends Lava\AbstractPackage
{
    public function register(Cargo\Container $app) {
        // $app['plates.views_path'] = null;
        // $app['plates.ext'] = 'php';
        // $app['plates'] = function($app) {
        //     return new Plates\Engine($app['plates.views_path'], $app['plates.ext']);
        // };
    }
}

//     /** injects pimple into the request */
//     function injectPlatesRequest($app) {
//         return function($req, $next) use ($app) {
//             $app['plates']->addData([
//                 'request' => $req,
//                 'app' => $app,
//             ]);
//             return $next($req);
//         };
//     }
//
//     /** Allows the returning of a 2-tuple of path and data */
//     function platesMarshalResponse($app) {
//         return function($result, $rf, $req, $next) use ($app) {
//             $matches = isTuple($result, "string", "array");
//             if (!$matches) {
//                 return $next($result, $rf, $req, $next);
//             }
//
//             list($template, $data) = $result;
//             return $rf(200, [], $app['plates']->render($template, $data));
//         };
//     }
//
//     function platesNotFoundHandler($app, $path) {
//         return function($req, $result, $next) use ($app, $path) {
//             if (!$app['plates']->exists($path)) {
//                 return $next($req, $result);
//             }
//
//             $rf = $app['response_factory'];
//             return $rf(404, [], $app['plates']->render($path, [
//                 'dispatch_result' => $result,
//             ]));
//         };
//     }
//     function platesExceptionHandler($app, $path) {
//         return function($req, $ex, $next) use ($app, $path) {
//             if (!$app['plates']->exists($path)) {
//                 return $next($req, $ex);
//             }
//
//             $rf = $app['response_factory'];
//             return $rf(500, [], $app['plates']->render($path, [
//                 'exception' => $ex,
//             ]));
//         };
//     }

// private $ext;
//     private $config;
//
//     public function __construct(array $config = []) {
//         $this->config = $config + [
//             '404' => 'errors/404',
//             '500' => 'errors/500',
//         ];
//     }
//
//     public function with(Http\App $app) {
//         $app->register(
//             new PlatesServiceProvider(),
//             Http\Util\arrayFromPrefix($this->config, 'plates.')
//         );
//
//         $app['stacks.not_found_handler']->push(platesNotFoundHandler(
//             $app,
//             $this->config['404']
//         ));
//         $app['stacks.exception_handler']->push(platesExceptionHandler(
//             $app,
//             $this->config['500']
//         ));
//         $app['stacks.marshal_response']->push(platesMarshalResponse($app));
//         $app->push(injectPlatesRequest($app));
//     }
