<?php
namespace Framework\Service;

use Framework\Model\UserToken;

/**
 */
class TokenAuthService {
    protected $app;
    //---------------------------------
    public function __construct($app) {
        $this->app         = $app;
        $this->user_token = new UserToken($app);
    }
    //---------------------------------
    public function generate_token($user_data) {
        $seq = md5(uniqid(time().'api_token_seq'));
        $token = $this->app['security.jwt.encoder']->encode(['name' => $user_data['user_name'], 'id' => $user_data['user_id'], 'seq' => $seq]);
        $this->user_token->save_token($user_data['user_id'], $seq);
        return $token;
    }
    public function remake_token($user_data) {
        $seq = md5(uniqid(time().'api_token_seq'));
        $token = $this->app['security.jwt.encoder']->encode(['name' => $user_data['user_name'], 'id' => $user_data['user_id'], 'seq' => $seq]);
        $this->user_token->update_token($user_data['user_id'], $seq);
        return $token;
    }
    //-------------------------------------------------------------
    public function make_logged_in_token($user_data) {
        $token_data= $this->user_token->get_token_by_id($user_data['user_id']);
        if(is_null($token_data) || empty($token_data)) {
            $token = $this->generate_token($user_data);
        } else {
            $token = $this->remake_token($user_data);
        }
        return $token;
    }
 
    //----------------------validate token----------------------------------
    public function validate_token($user_id, $seq) {
        $token_data = $this->user_token->get_token_by_id($user_id);
        if(isset($token_data) && is_array($token_data) && !empty($token_data)) {
            if($token_data['token_seq'] == $seq) {
                return $token_data;
            }
            return false;
        }
        return false;
    }
    //---------------------------------delete token --------------
    public function delete_token($user_id) {
        $this->user_token->delete($user_id);
    }
}
