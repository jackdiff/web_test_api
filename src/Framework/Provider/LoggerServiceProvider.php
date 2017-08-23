<?php
namespace Framework\Provider;

use Framework\Service\Log\BenchmarkLogger;
use Framework\Service\Log\FrameworkLogger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Silex\Application;
use Silex\ServiceProviderInterface;

class LoggerServiceProvider implements ServiceProviderInterface {
    private static $app;

    public function register(Application $app) {
        self::$app = $app;

        $app['monolog.logger.class'] = '\Monolog\Logger';

        $app['monolog.factory'] = $app->protect(
            function ($name) use ($app) {
                $logger = isset($app['monolog.' . $name])
                ? $app['monolog.' . $name]
                : array($app['monolog.framework']);

                return $logger;
            }
        );

        $app['monolog.benchmark'] = $app->share(
            function ($app) {
                $benchmarkLogger = new BenchmarkLogger($app);
                return $benchmarkLogger->getLogger();
            }
        );
        $app['monolog.framework'] = $app->share(
            function ($app) {
                $frameworkLogger = new FrameworkLogger($app);
                return $frameworkLogger->getLogger();
            }
        );

    }

    public function getBenchmarkHandlers(Application $app) {
        $handlers = array(
            new RotatingFileHandler($app['log_path'] . 'debug.log', 0, Logger::DEBUG, false, 01777),
            new RotatingFileHandler($app['log_path'] . 'info.log', 0, Logger::INFO, false, 01777),
            new RotatingFileHandler($app['log_path'] . 'warning.log', 0, Logger::WARNING, false, 01777),
            new RotatingFileHandler($app['log_path'] . 'error.log', 0, Logger::ERROR, false, 01777),
        );

        return $handlers;
    }

    public function boot(Application $app) {
        self::$app = $app;
    }

    public static function benchmark($message) {
        $app = self::$app;
        return $app['monolog.factory']('benchmark')->info($message);
    }

    public static function info($message) {
        $app = self::$app;
        return $app['monolog.factory']('framework')->info($message);

    }

    public static function debug($message) {
        $app = self::$app;
        return $app['monolog.factory']('framework')->debug($message);

    }

    public static function error($message) {
        $app = self::$app;
        return $app['monolog.factory']('framework')->error($message);

    }

}
