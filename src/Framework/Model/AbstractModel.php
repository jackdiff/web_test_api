<?php
namespace Framework\Model;

/**
 */
class AbstractModel {
    protected $table;
    protected $database;
    protected $app;

    /**
     */
    public function __construct($app) {
        $this->app = $app;
        $this->database = $app['database']->getConnection($this->table);
    }

    public function getConnection() {
        return $this->database;
    }

}