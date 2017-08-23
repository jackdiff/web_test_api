<?php
namespace Framework\Service\Log;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Silex\Application;

class BenchmarkLogger {
    private $logger;
    public function __construct(Application $app) {
        $handlers = array(
            new RotatingFileHandler($app['log_path'] . $app['env'] . '.benchmark.log', 0, Logger::INFO, false),
        );

        $logFormat = '[%datetime%] ' .
            '%extra.ip% ' .
            '%extra.http_method% ' .
            '%extra.server% ' .
            '%extra.url% ' .
            '%extra.referrer% ' .
            '%extra.memory_usage% ' .
            "%message%\n";

        $formatter = new LineFormatter($logFormat);
        foreach ($handlers as $handler) {
            $handler->setFilenameFormat('{date}.{filename}', 'Ymd');
            $handler->setFormatter($formatter);
        }

        $logger = new $app['monolog.logger.class']('benchmark');

        foreach ($handlers as $handler) {
            $logger->pushHandler($handler);
        }

        $logger->pushProcessor(new \Monolog\Processor\WebProcessor());
        $logger->pushProcessor(new \Monolog\Processor\MemoryUsageProcessor(true, false));

        $this->logger = $logger;
    }

    public function getLogger() {
        return $this->logger;
    }

}