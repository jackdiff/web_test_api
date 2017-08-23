<?php
namespace Framework\Service;

use Framework\AppConstants;
use Framework\Exception\FrameworkException;
use Framework\Model\UserModel;
use Framework\Provider\LoggerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 */
class UserService {
    protected $app;
    //--------------------------------------------------------
    public function __construct($app) {
        $this->app        = $app;
        $this->userModel  = new UserModel($app);
    }

    //--------------------------------------------------------
    public function login($email, $password) {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if(!$email) {
            return ['error' => true, 'code' => AppConstants::CODE_EMAIL_NOT_VALID, 'message' => AppConstants::MESSAGE_EMAIL_NOT_VALID];
        }
        $user = $this->userModel->get_by_email($email);
        if (!$user ||  empty($user)) {
            return ['error' => true, 'code' => AppConstants::CODE_EMAIL_NOT_EXIST, 'message' => AppConstants::MESSAGE_EMAIL_NOT_EXIST];
        }
        $password_hash = md5($password);
        if ($user['password'] != $password_hash) {
            return ['error' => true, 'code' => AppConstants::CODE_PASSWORD_NOT_MATCHED, 'message' => AppConstants::MESSAGE_PASSWORD_NOT_MATCHED];
        }
        $token = $this->app['service.token']()->make_logged_in_token($user);
        return [
            'token'     => $token
        ];
    }

    //--------------------------------------------------------
    public function logout() {
        if(isset($this->app['user_id']) && !is_null($this->app['user_id'])) {
            $this->app['service.token']()->delete_token($this->app['user_id']);
        }
        return;
    }

    public function get_user_profile($user_id) {
        $master = $this->userModel->get_by_id($user_id);
        if(!$master) {
            return [ 'error' => true, 'code' => AppConstants::CODE_USER_NOT_EXIST, 'message' => AppConstants::MESSAGE_USER_NOT_EXIST];
        }
        //profile
        $profile = $this->userModel->get_user_param($user_id);
        $profile = array_merge($master, $profile);
        $this->_remove_unnessary_data($profile);
        return [
            'profile' => $profile
        ];
    }

    private function _remove_unnessary_data(&$user_data) {
        unset($user_data['password']);
    }

    public function update_profile($params) {
        $validator = $this->app['service.user.validate.param']()->validate_update($params);
        if(!$validator->is_pass()) {
            return [
                'error' => true,
                'code' => $validator->get_error_code(),
                'message' => $validator->get_error_message()
            ];
        }
        $this->userModel->update_user_param($this->app['user_id'], $params);

        return $this->get_user_profile($this->app['user_id']);
    }
}
