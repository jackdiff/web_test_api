<?php

namespace Framework\Provider;

use Framework\Service\DatabaseService;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 */
class DatabaseServiceProvider implements ServiceProviderInterface {

    public function register(Application $app) {

        $app['database'] = $app->share(
            function ($app) {
                return new DatabaseService($app);
            }
        );
    }

    public function boot(Application $app) {
    }
}
