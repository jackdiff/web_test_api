<?php
namespace Framework\Model;

use Framework\Provider\LoggerServiceProvider;
use Doctrine\DBAL\Connection;
use Framework\AppConstants;
/**
 */
class UserModel extends AbstractModel {

    
    public function __construct($app) {
        $this->table = 'user_masters';
        $this->table_param = 'user_params';
        parent::__construct($app);
    }

    //------------- user master ---------------------------
    public function get_by_email($email) {
        $queryBuilder = $this->getConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($this->table)
            ->where('email = :em')
            ->setParameter('em', $email);

        $user = $queryBuilder->execute()->fetch();
        return $user;
    }
    public function get_by_id($id) {
        $queryBuilder = $this->getConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($this->table)
            ->where('user_id = :id')
            ->setParameter('id', $id);

        $user = $queryBuilder->execute()->fetch();
        return $user;
    }

    //----------------get user param info--------------------------------
    public function get_user_param($user_id) {
        $queryBuilder = $this->getConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($this->table_param)
            ->where('user_id = :user_id')
            ->setParameter('user_id', $user_id);

        $user = $queryBuilder->execute()->fetch();
        return $user;
    }
     //----------------update user param info--------------------------------
    public function update_user_param($user_id, $params) {
        $now_time = date('Y-m-d H:i:s', time());
        $queryBuilder = $this->getConnection()->createQueryBuilder();
        foreach ($params as $key => $val) {
            $queryBuilder->set($key, ':'.$key)->setParameter(':'.$key, $val);
        }
        $queryBuilder->set('updated_at', ':upd')->setParameter(':upd', $now_time);
        $queryBuilder
            ->update($this->table_param)
            ->where('user_id = :user_id')
            ->setParameter('user_id', $user_id);
        $queryBuilder->execute();
    }
}