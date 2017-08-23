<?php

namespace Framework\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class FrameworkResponse extends JsonResponse {
    private $success  = true;
    private $mainData = null;
    private $code     = null;
    private $message  = null;

    public function __construct($success, $mainData, $code, $message, $status = 200, array $headers = array()) {
        $this->success  = $success;
        $this->mainData = $mainData;
        $this->code     = $code;
        $this->message  = $message;

        $data     = array();
        $fieldMap = array(
            'success' => $success ? 1 : 0,
            'data'    => $mainData,
            'code'    => $code,
            'message' => $message,
        );

        foreach ($fieldMap as $key => $value) {
            if ($value !== null) {
                $data[$key] = $value;
            }
        }

        parent::__construct($data, $status, $headers);
    }
}
