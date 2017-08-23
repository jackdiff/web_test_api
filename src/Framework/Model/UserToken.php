<?php
namespace Framework\Model;

use Framework\Model\AbstractModel;

/**
 */
class UserToken extends AbstractModel {

    /**
     */
    public function __construct($app) {
        $this->table = 'user_tokens';
        parent::__construct($app);
    }

    //-------------------------get token---------------------------------
    public function get_token_by_id($user_id) {
        $queryBuilder = $this->getConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($this->table)
            ->where('user_id = :uid')
            ->setParameter('uid', $user_id);

        $user_token = $queryBuilder->execute()->fetch();
        return $user_token;
    }
    //-------------------------save new token---------------------------------
    public function save_token($user_id, $token) {
        $now_time = date('Y-m-d H:i:s', time());
        $queryBuilder = $this->database->createQueryBuilder();
        return $queryBuilder
            ->insert($this->table)
            ->values(
                [
                    'user_id'   => ':uid',
                    'token_seq'  => ':token',
                    'created_at' => ':up',
                    'updated_at' => ':up'
                ]
            )
            ->setParameter(':uid', $user_id)
            ->setParameter(':token', $token)
            ->setParameter(':up', $now_time)
            ->execute()
        ;
    }
    //-------------------------update token---------------------------------
    public function update_token($user_id, $new_token) {
        $now_time = date('Y-m-d H:i:s', time());
        $queryBuilder = $this->database->createQueryBuilder();
        return $queryBuilder
            ->update($this->table)
            ->set('token_seq',':token')
            ->set('updated_at',':up')
            ->where('user_id = :uid')
            ->setParameter(':token', $new_token)
            ->setParameter(':up', $now_time)
            ->setParameter(':uid', $user_id)
            ->execute()
        ;
    }
    //-------------------------delete token---------------------------------
    public function delete($user_id) {
        $queryBuilder = $this->database->createQueryBuilder();
        return $queryBuilder
            ->delete($this->table)
            ->where('user_id = :uid')
            ->setParameter('uid', $user_id)
            ->execute()
        ;
    }
}