<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class UserForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @return array
     */
    public function rules()
    {
        return [
          [['username', 'email'], 'required'],
          ['email', 'email'],
          ['password', 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password'
        ];
    }
}