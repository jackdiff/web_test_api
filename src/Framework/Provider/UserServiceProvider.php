<?php
namespace Framework\Provider;

use Framework\Service\UserService;
use Framework\Service\TokenAuthService;
use Framework\Service\Validate\ValidateUserParamService;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Silex\Component\Security\Core\Encoder\JWTEncoder;

class UserServiceProvider implements ServiceProviderInterface {
    public function register(Application $app) {
        $app['service.user'] = $app->protect(
            function () use ($app) {
                $userService   = new UserService($app);
                return $userService;
            }
        );
        $app['service.token'] = $app->protect(
            function () use ($app) {
                $tokenService   = new TokenAuthService($app);
                return $tokenService;
            }
        );

        $app['service.user.validate.param'] = $app->protect(
            function () use ($app) {
                $validateService   = new ValidateUserParamService($app);
                return $validateService;
            }
        );

        $app['security.jwt'] = array_replace_recursive([
            'secret_key' => 'default_secret_key',
            'life_time' => 86400,
            'algorithm'  => ['HS256'],
            'options' => [
                'username_claim' => 'name',
                'header_name' => 'SECURITY_TOKEN_HEADER',
                'token_prefix' => null,
            ]
        ], $app['security.jwt']);
        $app['security.jwt.encoder'] = function() use ($app) {
            return new JWTEncoder($app['security.jwt']['secret_key'], $app['security.jwt']['life_time'], $app['security.jwt']['algorithm']);
        };
    }

    public function boot(Application $app) {
    }
}
