<?php

namespace Framework\Response;

use Symfony\Component\HttpFoundation\Response;

class ResponseBuilder implements ResponseBuilderInterface {
    private $success  = true;
    private $mainData = null;
    private $code     = null;
    private $message  = null;
    private $status   = Response::HTTP_OK;
    private $headers  = array();

    public function success() {
        $this->success = true;
        return $this;
    }

    public function fail() {
        $this->success = false;
        return $this;
    }

    public function setMainData($mainData) {
        $this->mainData = $mainData;
        return $this;
    }

    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function setHeaders(array $headers) {
        $this->headers = $headers;
        return $this;
    }

    public function build() {
        return new FrameworkResponse(
            $this->success,
            $this->mainData,
            $this->code,
            $this->message,
            $this->status,
            $this->headers);
    }
}