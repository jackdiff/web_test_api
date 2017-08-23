<?php

/*

 */

namespace Framework\Provider;

use Framework\Service\MemSession;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 */
class SessionServiceProvider implements ServiceProviderInterface {
    const AUTH_VALIDATE_CREDENTIALS = 'auth.validate.credentials';
    const AUTH_VALIDATE_TOKEN       = 'auth.validate.token';
    const AUTH_NEW_TOKEN            = 'auth.new.token';

    public function register(Application $app) {

        $app['session'] = $app->share(
            function ($app) {
                return new MemSession($app);
            }
        );
        //---- register token service ---------
        $app[self::AUTH_VALIDATE_CREDENTIALS] = $app->protect(function ($user, $pass) {
            return $this->validateCredentials($user, $pass);
        });
        $app[self::AUTH_VALIDATE_TOKEN] = $app->protect(function ($token) {
            return $this->validateToken($token);
        });
        $app[self::AUTH_NEW_TOKEN] = $app->protect(function ($user) {
            return $this->getNewTokenForUser($user);
        });
    }
    //-------------------- token -------------------------------
    private function validateCredentials($user, $pass)
    {
        return $user == $pass;
    }
    private function validateToken($token)
    {
        return $token == 'a';
    }
    private function getNewTokenForUser($user)
    {
        return 'a';
    }
    //-------------------- inherited func ----------------------
    public function boot(Application $app) {
    }

}
