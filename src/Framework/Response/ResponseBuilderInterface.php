<?php

namespace Framework\Response;

interface ResponseBuilderInterface {
    public function success();

    public function fail();

    public function setMainData($mainData);

    public function setCode($code);

    public function setMessage($message);

    public function setStatus($status);

    public function setHeaders(array $headers);

    // return FrameworkResponse object
    public function build();
}

