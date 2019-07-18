<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\validators\EmailValidator;

class UserCreateForm extends Model
{
    public $usernames = [];
    public $emails = [];
    public $passwords = [];

    public function rules()
    {
        return [
            [['usernames', 'emails', 'passwords'], 'requireArray'],
            ['emails', 'requireEmail']
        ];
    }

    /**
     * @param $attributes
     * @param $params
     * @param $validator
     */
    public function requireArray($attributes, $params, $validator)
    {
            foreach ($this->attributes as $attribute) {
                if (empty($attribute)) {
                    die("merge");
                    $validator->addError($this, $attribute, 'The field must not be empty');
                }
            }
    }

    /**
     * @param $attributes
     * @param $params
     * @param $validator
     * @throws \yii\base\NotSupportedException
     */
    public function requireEmail($attributes, $params, $validator)
    {
        $emailValidator = new EmailValidator();
        foreach ($this->attributes as $attribute) {
            if (!$emailValidator->validateValue($attribute)) {
                $validator->addError($this, $attribute, 'The email is not valid');
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'usernames' => 'Username',
            'emails' => 'Email',
            'passwords' => 'Password'
        ];
    }
}