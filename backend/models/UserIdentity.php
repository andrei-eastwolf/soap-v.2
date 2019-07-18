<?php

namespace backend\models;

use common\models\User;
use Yii;
use yii\base\Component;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;

/**
 * Class UserIdentity
 * @package backend\models\
 */
class UserIdentity extends Component
{
    public $username;
    public $password;
    public $error_code;
    public $auth_key;

    const ERROR_NONE = 1;
    const ERROR_LOGIN = 0;

    /**
     * UserIdentity constructor.
     * @param $username
     * @param $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * function to check credentials and log in
     */
    public function authenticate() {
        $data = User::findByUsername($this->username);
        if ($data) {
            if ($data->validatePassword($this->password)) {
                $this->error_code = self::ERROR_NONE;
                $this->auth_key = $data->auth_key;
            } else {
                $this->error_code = self::ERROR_LOGIN;
            }
        } else {
            $this->error_code = self::ERROR_LOGIN;
        }
    }
}