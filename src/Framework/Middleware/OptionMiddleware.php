<?php
namespace Framework\Middleware;

use Framework\Provider\LoggerServiceProvider;
use Framework\Service\MemSession;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
/*
 * Session manager
 */
class OptionMiddleware extends AbstractMiddleware {
    //--------------------------------------------------------------------------------------
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = false) {

        if ($request->getMethod() == 'OPTIONS') {
           $response = new JsonResponse();
           $response->headers->set("Access-Control-Allow-Origin","*");
           $response->headers->set("Access-Control-Allow-Methods","GET,POST,PUT,DELETE,OPTIONS");
           $response->headers->set("Access-Control-Allow-Headers","Content-Type,X-Token,X-Access-Token");
           $response->setStatusCode(200);
           return $response->send();
        }

        $response = $this->appDecorator->handle($request, $type, $catch);

        return $response;
    }
}
