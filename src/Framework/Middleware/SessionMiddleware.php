<?php
namespace Framework\Middleware;

use Framework\Provider\LoggerServiceProvider;
use Framework\Service\MemSession;
use Symfony\Component\HttpFoundation\Request;
/*
 * Session manager
 */
class SessionMiddleware extends AbstractMiddleware {
    const TOKEN_HEADER_KEY     = 'X-Access-Token';
    //--------------------------------------------------------------------------------------
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = false) {
        $token = $this->get_jwt_info($request);
        if($token && $token->id && $token->seq) {
            $user_token = $this->app['service.token']()->validate_token($token->id, $token->seq);
            if($user_token) {
                $this->app['user_id'] = $user_token['user_id'];
                $this->app['user.token.data'] = $user_token;
            }
        }
        $response = $this->appDecorator->handle($request, $type, $catch);

        return $response;
    }

    private function get_jwt_info(Request $request) {
        $request_token = $request->headers->get(self::TOKEN_HEADER_KEY, 'Authorization');
        if(empty($request_token)) {
          return null;
        }
        try {
            $token = trim(str_replace('Bearer', "", $request_token));
            $decoded = $this->app['security.jwt.encoder']->decode($token);
            return $decoded;
        }catch(\Exception $ex) {
          return null;
        }
    }
}
