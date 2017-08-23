<?php
namespace Framework\Middleware;

use Framework\Utils\Helper;
use Symfony\Component\HttpFoundation\Request;

class DatabaseMiddleware extends AbstractMiddleware {

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = false) {
        $method        = $request->getMethod();
        $isReadRequest = true;
        if ($method !== 'GET') {
            $isReadRequest = false;
        }

        $this->app['database']->setReadOnlyRequest($isReadRequest);

        if (!$isReadRequest) {
            $this->app['database']->beginTransaction();
        }
        try {
            $response = $this->appDecorator->handle($request, $type, false);
            $this->app['database']->commit();
        } catch (\Exception $e) {
            // AbstractModel::rollBack();
            $this->app['database']->rollback();
            throw $e;
        }

        return $response;
    }

}
