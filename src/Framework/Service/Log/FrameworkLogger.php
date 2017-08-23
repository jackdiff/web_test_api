<?php
namespace Framework\Service\Log;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Silex\Application;

class FrameworkLogger {
    private $logger;

    public function __construct(Application $app) {
        $handlers = array();

        if ($app['log.level'] <= Logger::DEBUG) {
            $handlers[] = new RotatingFileHandler($app['log_path'] . $app['env'] . '.debug.log', 0, Logger::DEBUG, false);
        }
        if ($app['log.level'] <= Logger::INFO) {
            $handlers[] = new RotatingFileHandler($app['log_path'] . $app['env'] . '.info.log', 0, Logger::INFO, false);
        }
        if ($app['log.level'] <= Logger::WARNING) {
            $handlers[] = new RotatingFileHandler($app['log_path'] . $app['env'] . '.warning.log', 0, Logger::WARNING, false);
        }
        if ($app['log.level'] <= Logger::ERROR) {
            $handlers[] = new RotatingFileHandler($app['log_path'] . $app['env'] . '.error.log', 0, Logger::ERROR, false);
        }

        $formatter = new LineFormatter("[%datetime%] %extra.server% %extra.url% %extra.user_id% %message% \n");

        foreach ($handlers as $handler) {
            $handler->setFilenameFormat('{date}.{filename}', 'Ymd');
            $handler->setFormatter($formatter);
        }

        $logger = new $app['monolog.logger.class']('framework');
        $logger->pushProcessor(new \Monolog\Processor\WebProcessor());
        $logger->pushProcessor(new \Framework\Service\Log\GameLogProcessor());

        foreach ($handlers as $handler) {
            $logger->pushHandler($handler);
        }

        $this->logger = $logger;
    }

    public function getLogger() {
        return $this->logger;
    }

}