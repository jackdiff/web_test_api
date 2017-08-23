<?php
namespace Framework\Middleware;

use Framework\AppConstants;
use Framework\Provider\LoggerServiceProvider;
use Framework\Utils\Helper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/*
 * Session manager
 */
class AuthorizationMiddleware extends AbstractMiddleware {

    //--------------------------------------------------------------------------------------
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = false) {

        $needAuthorized = Helper::is_matching_request($request, getAuthorizedRequests());
        if ($needAuthorized) {
            $user_data = isset($this->app['user.token.data']) ? $this->app['user.token.data'] : NULL;
            $isAuthenticated = isset($user_data) && !is_null($user_data) && isset($user_data['user_id']);

            if (!$isAuthenticated) {
                $data    = array(
                    'code' => AppConstants::STATUS_NOTLOGIN,
                    'success' => false,
                    'message' => 'Bạn cần đăng nhập để thực hiện thao tác này!',
                );
                return new JsonResponse($data);
            }
        }
        $response = $this->appDecorator->handle($request, $type, $catch);

        return $response;
    }
}
