<?php

// authorization requests which are only available for authorized (logged in) users
if (!function_exists('getAuthorizedRequests')) {
    function getAuthorizedRequests() {
        return array(
            '^/me.*$',
        );
    }
}