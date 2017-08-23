<?php
namespace Framework\Service\Validate;

use Framework\AppConstants;
use Framework\Provider\LoggerServiceProvider;
/**
 */
class Validator {
    protected $success;
    protected $error_code;
    protected $error_message;
    //---------------------------------
    public function __construct($success_flg, $err_code = null, $err_msg = null) {
        $this->success = $success_flg;
        $this->error_code = $err_code;
        $this->error_message = $err_msg;
    }

    public function get_error_code() {
        return $this->error_code;
    }

    public function get_error_message() {
        return $this->error_message;
    }

    public function is_pass() {
        return $this->success;
    }
}