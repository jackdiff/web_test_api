<?php
namespace Framework\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Framework\Provider\LoggerServiceProvider;
use Framework\AppConstants;
/**
 */
class UserController {
    const ALLOW_USER_UPDATE_FIELDS  = ['name','dob','address','phone_number'];
    //---------------------login------------------------------------------------
    public function login(Application $app) {
        $email          = $app['request']->request->get('email');
        $password       = $app['request']->request->get('password');
        $data           = $app['service.user']()->login($email, $password);
        if(isset($data['error']) && $data['error'] === true) {
            $response = [
                'success' => false,
                'code' => $data['code'],  
                'message' => $data['message'],
            ];
            return new JsonResponse($response);
        }
        $response = [
                'token' => $data['token'],
                'success' => true, 
                'code'     => AppConstants::CODE_REQUEST_SUCCESS,
                'message' => 'user '.$email.' logged in'
            ];
        return new JsonResponse($response);
    }
    //---------------------logout------------------------------------------------
    public function logout(Application $app) {
        $data        = $app['service.user']()->logout();
        $response = [
                'success' => true, 
                'code'     => AppConstants::CODE_REQUEST_SUCCESS,
                'message' => 'logged out'
            ];
        return new JsonResponse($response);
    }

    public function get_profile(Application $app) {
        $user_id = $app['request']->get('user_id');
        $data = $app['service.user']()->get_user_profile($user_id);
        if(isset($data['error']) && $data['error'] == true) {
            $response = [
                'success' => false,
                'code' => $data['code'],  
                'message' => $data['message'],
            ];
            return new JsonResponse($response);
        }
        $response = [
            'user_data' => $data['profile'],
            'success' => true, 
            'code'     => AppConstants::CODE_REQUEST_SUCCESS,
            'message' => ''
        ];
        return new JsonResponse($response);
    }

    public function get_my_profile(Application $app) {
        $user_id = $app['user_id'];
        $data = $app['service.user']()->get_user_profile($user_id);
        if(isset($data['error']) && $data['error'] == true) {
            $response = [
                'success' => false,
                'code' => $data['code'],  
                'message' => $data['message'],
            ];
            return new JsonResponse($response);
        }
        $response = [
            'user_data' => $data['profile'],
            'success' => true, 
            'code'     => AppConstants::CODE_REQUEST_SUCCESS,
            'message' => ''
        ];
        return new JsonResponse($response);
    }

    public function update_profile(Application $app) {
        $param_data = [];
        $param_keys = self::ALLOW_USER_UPDATE_FIELDS;
        foreach ($param_keys as $val) {
            $req_value = $app['request']->request->get($val);
            if($req_value) {
                $param_data[$val]    = $req_value;
            }
        }

        $updated_result = $app['service.user']()->update_profile($param_data);
        if(isset($updated_result['error']) && $updated_result['error'] == true) {
            $response = [
                'success' => false, 
                'code'     => $updated_result['code'],
                'message' => $updated_result['message']
            ];
            return new JsonResponse($response);
        }
        $response = array(
            'success' => true, 
            'user_data' => $updated_result['profile'],
            'code'     => AppConstants::CODE_REQUEST_SUCCESS,
            'message' => '');
        return new JsonResponse($response);
    }
}
