<?php

namespace Framework\Service\Log;

class GameLogProcessor {
    private $userId = -1;

    public function __construct() {

    }

    /*
     *
     */
    public function __invoke(array $record) {

        // TODO: When integrating session management, will get this one from session
        // Should get user_id from Session
        $record['extra']['user_id'] = -1;

        return $record;
    }
}
