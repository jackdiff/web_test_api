<?php
namespace Framework\Service;

use Framework\Provider\LoggerServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

/**
 */
class DatabaseService {
    private $app;

    private $isReadOnlyRequest      = false;
    private $transactionEnabled     = false;
    private $tableConnectionMapping = array();
    private $connectionConfigs      = array();

    /**
     */
    public function __construct($app) {
        $this->app               = $app;
        $this->connectionConfigs = $this->getConnectionConfigs();

        $this->app->register(new DoctrineServiceProvider(), array('dbs.options' => $this->connectionConfigs));
        LoggerServiceProvider::info("Initialize database successful !!! ");
    }

    /**
     */
    public function getConnection($tableName) {

        if ($this->app['env'] == 'test') {
            $this->tableConnectionMapping = null;
        }

        if (empty($this->tableConnectionMapping)) {
            // $connectionConfigs = getConnectionConfigs($this->app);
            foreach ($this->connectionConfigs as $name => $connectionConfig) {
                foreach ($connectionConfig['tables'] as $table) {
                    if ($connectionConfig['isReadOnly'] == true) {
                        $this->tableConnectionMapping[$table]['slave'][] = $name;
                    } else {
                        $this->tableConnectionMapping[$table]['master'][] = $name;
                    }
                }
            }
        }

        if (!isset($this->tableConnectionMapping[$tableName])) {
            throw new \Exception("Can not find the table! $tableName");
        }

        $connectionType = ($this->isReadOnlyRequest) ? 'slave' : 'master';
        $randKey        = array_rand($this->tableConnectionMapping[$tableName][$connectionType], 1);
        $connectionName = $this->tableConnectionMapping[$tableName][$connectionType][$randKey];

        return $this->app['dbs'][$connectionName];
    }

    public function setReadOnlyRequest($isReadOnly) {
        $this->isReadOnlyRequest = $isReadOnly;
    }

    public function beginTransaction() {
        if ($this->isReadOnlyRequest) {
            return;
        }
        $this->transactionEnabled = true;
        // $connectionConfigs        = getConnectionConfigs($this->app);
        foreach ($this->connectionConfigs as $name => $config) {
            $this->app['dbs'][$name]->beginTransaction();
        }
    }

    public function commit() {
        if ($this->app['env'] == 'test') {
            return;
        }

        if (!$this->transactionEnabled) {
            return;
        }
        // $connectionConfigs = getConnectionConfigs($this->app);
        foreach ($this->connectionConfigs as $name => $config) {
            $this->app['dbs'][$name]->commit();
        }
    }

    public function rollBack() {
        if (!$this->transactionEnabled) {
            return;
        }
        // $connectionConfigs = getConnectionConfigs($this->app);
        foreach ($this->connectionConfigs as $name => $config) {
            $this->app['dbs'][$name]->rollBack();
        }
    }

    private function getConnectionConfigs() {

        $app       = $this->app;
        $databases = $app['DATABASE'];

        foreach ($databases as $name => $config) {
            // master connection
            $config['driver']     = $app['DATABASE_DRIVER'];
            $config['user']       = $app['DATABASE_USERNAME'];
            $config['password']   = $app['DATABASE_PASSWORD'];
            $config['charset']    = $app['DATABASE_CHARSET'];
            $config['port']       = $app['DATABASE_PORT'];
            $config['host']       = $config['masterHost'];
            $config['isReadOnly'] = false;

            $connectionName                     = $name . '_master';
            $connectionConfigs[$connectionName] = $config;

            // slave connection
            $slaveCount = 1;
            foreach ($config['slaveHost'] as $slaveHost) {
                $config['host']                     = $slaveHost;
                $config['isReadOnly']               = true;
                $connectionName                     = $name . '_slave_' . $slaveCount;
                $connectionConfigs[$connectionName] = $config;
                $slaveCount++;
            }
        }

        return $connectionConfigs;
    }

}
