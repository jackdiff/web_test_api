<?php
namespace Framework\Service\Validate;

use Framework\AppConstants;
use Framework\Utils\Helper;
use Framework\Provider\LoggerServiceProvider;
/**
 */
class ValidateUserParamService {
    protected $app;
    //---------------------------------
    public function __construct($app) {
        $this->app         = $app;
        $this->USER_PARAMS = [
            'name'          => 'Tên',
            'dob'           => 'Ngày sinh', 
            'address'       => 'Địa chỉ', 
            'phone_number'  => 'Số điện thoại'
        ];
        $this->ALLOW_UPDATE_USER_PARAMS = array('name', 'phone_number', 'dob', 'address');
        $this->LIMITATION = [
            'name' => 45,
            'address' => 150,
        ];
        $this->PATTERNED_PARAMS = [
            'phone_number' => '/([0-9]){10,11}/',
        ];
        $this->NOT_EMPTY_PARAMS = [
            'name'
        ];
        $this->DATE_PARAMS = [
            'dob'
        ];
    }

    public function validate_update($params) {
        foreach($params as $k => $val) {
            if(!in_array($k, $this->ALLOW_UPDATE_USER_PARAMS)) {
                return new Validator(false, AppConstants::CODE_INFO_NOT_ALLOW_UPDATE, Helper::compose_message_with_params( AppConstants::MESSAGE_INFO_NOT_ALLOW_UPDATE, [$this->USER_PARAMS[$k]]));
            }

            if(in_array($k, array_keys($this->LIMITATION))) {
                if(strlen($val) > $this->LIMITATION[$k]) {
                    return new Validator(false, AppConstants::CODE_INFO_UPDATE_OVER_LIMITATION, Helper::compose_message_with_params( AppConstants::MESSAGE_INFO_UPDATE_OVER_LIMITATION, [$this->USER_PARAMS[$k], $this->LIMITATION[$k]]));
                }
            }

            if(in_array($k, array_keys($this->PATTERNED_PARAMS))) {
                preg_match($this->PATTERNED_PARAMS[$k], $val, $matches);
                if(empty($matches) || $matches[0] != $val) {
                    return new Validator(false, AppConstants::CODE_INFO_UPDATE_NOT_VALID, Helper::compose_message_with_params( AppConstants::MESSAGE_INFO_UPDATE_NOT_VALID, [$this->USER_PARAMS[$k]]));
                }
            }

            if(in_array($k, $this->NOT_EMPTY_PARAMS) && empty($val)) {
                return new Validator(false, AppConstants::CODE_INFO_UPDATE_EMPTY, Helper::compose_message_with_params( AppConstants::MESSAGE_INFO_UPDATE_EMPTY, [$this->USER_PARAMS[$k]]));
            }

            if(in_array($k, $this->DATE_PARAMS) && Helper::validate_date($val) == false) {
                return new Validator(false, AppConstants::CODE_INFO_DATE_UPDATE_NOT_VALID, Helper::compose_message_with_params( AppConstants::MESSAGE_INFO_DATE_UPDATE_NOT_VALID, [$this->USER_PARAMS[$k]]));
            }

        }
        return new Validator(true);
    }
}
