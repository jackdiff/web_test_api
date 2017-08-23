<?php

namespace Framework\Utils;

use DateTime;
use Framework\Provider\LoggerServiceProvider;

class Helper {

    public static function is_matching_request($request, $matchedUris) {
        $uri    = $request->getRequestUri();
        $isMatched = false;

        // check URI
        foreach ($matchedUris as $requestUri) {
            $requestUri = str_replace("/", "\/", $requestUri);
            preg_match("/$requestUri/", $uri, $matches);
            if (count($matches) > 0) {
                $isMatched = true;
                break;
            }
        }
        return $isMatched;
    }

    public static function compose_message_with_params($string_frm, $vals) {
        return vsprintf($string_frm, $vals);
    }

    public static function validate_date($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
