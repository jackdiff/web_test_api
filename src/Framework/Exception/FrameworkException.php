<?php
namespace Framework\Exception;

use Exception;

/*
 * Standardlize message format
 * Log every exception
 * FRAMEWORK ERROR CODE FORMAT
 *      [HTTPCODE][MODULECODE][INCREASENUMBER]
 *      HTTPCODE = 400,401,404,...,500
 *      MODULECODE (XX): USER(00), BANK(01), ...
 */
class FrameworkException extends Exception {
    // Internal server error
    const UNKNOWN_ERROR       = [500, 5000000, "unknown_error"];
    const USER_EXISTED_ERROR  = [500, 5000001, "user_existed_error"];
    const LANGUAGE_FILE_ERROR = [500, 5000002, "language_file_error"];
    const LANGUAGE_KEY_ERROR  = [500, 5000003, "language_key_error"];

    // Authenticate problem
    const INVALID_AUTHENTICATION = [400, 4010001, "invalid_authentication"];

    // Not found resource problem
    const USER_NOT_FOUND = [404, 4040001, "user_not_found"];

    private $errorMessage;

    public function __construct($errorMessage) {
        parent::__construct();
        $this->errorMessage = $errorMessage;
    }

    public function getHttpCode() {
        return $this->errorMessage[0];
    }

    public function getFrameworkCode() {
        return $this->errorMessage[1];
    }

    public function getFrameworkMessage() {
        return $this->errorMessage[2];
    }

}
