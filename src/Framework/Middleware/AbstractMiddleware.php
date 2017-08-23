<?php
namespace Framework\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AbstractMiddleware implements HttpKernelInterface {
    protected $appDecorator;
    protected $app;

    public function __construct(HttpKernelInterface $appDecorator, \Silex\Application $app) {
        $this->app          = $app;
        $this->appDecorator = $appDecorator;

    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = false) {

        // Add logic here to be placed in the request response cycle
        $response = $this->appDecorator->handle($request, $type, $catch);

        return $response;
    }

}
