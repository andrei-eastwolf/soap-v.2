<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\User;
use frontend\models\UserCreateForm;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use backend\models\UserIdentity;

/**
 * Class ApiController
 * @package backend\controllers
 */
class ApiController extends Controller
{
    /**
     * Disabled csrf validation to perform requests from client side.
     */
    public $enableCsrfValidation = false;

    /**
     * added soapApi action
     *
     * @inheritDoc
     *
     * @return array
     */
    public function actions()
    {
        return [
            'soapApi' => [
                'class' => 'subdee\soapserver\SoapAction',
            ]
        ];
    }

    /**
     * @return int
     * @soap
     */
    public function pingSOAP()
    {
        return time();
    }

    /**
     * @param string the username
     * @param string the password
     *
     * @return string|bool the login
     * @soap
     */
    public function login($username, $password)
    {
        $identity = new UserIdentity($username, $password);

        $identity->authenticate();
        return $identity->auth_key;

        if ($identity->error_code == UserIdentity::ERROR_NONE) {
            return $identity->auth_key;
        }

        return false;
    }

    /**
     * @return int
     * @soap
     */
    public function pingAuth()
    {
        return time();
    }

    /**
     * @param string $auth_key
     *
     * @return string
     * @soap
     */
    public function getAllUsers($auth_key)
    {
        $data = User::find()->asArray()->all();
        $verify = User::findOne(['auth_key' => $auth_key]);
        if (!empty($verify)) {
            return json_encode($data);
        }

        return json_encode(['message' => "Wrong credentials"]);
    }

    /**
     * @param string $auth_key
     * @param integer $user_id
     *
     * @return string
     * @soap
     */
    public function getOneUser($auth_key, $user_id)
    {
        $verify = User::findOne(['auth_key' => $auth_key]);

        if (!empty($verify)) {
            $data = User::find()->where(['id' => $user_id])->asArray()->one();
            if (!empty($data)) {
                return json_encode($data);
            }

            return json_encode(['message' => "User doesn't exist"]);
        }
        return json_encode(['message' => "Wrong credentials"]);
    }

    /**
     * @param string $auth_key
     * @param integer $user_id
     * @param string $update
     * @soap
     */
    public function updateUser($auth_key, $user_id, $update)
    {
        $verify = User::findOne(['auth_key' => $auth_key]);

        if (!empty($verify)) {
            $update = json_decode($update, true);
            $model = User::find()->where(['id' => $user_id])->one();
            $model->username = $update['username'];
            $model->email = $update['email'];
            if ($update['password']) {
               $model->setPassword($update['password']);
            }
            if ($model->validate()) {
                $model->save();
            }
        }
    }

    /**
     * @param string $auth_key
     * @param string $create
     *
     * @return string
     * @soap
     */
    public function createUsers($auth_key, $create)
    {
        $verify = User::findOne(['auth_key' => $auth_key]);

        if (!empty($verify)) {
            $create = json_decode($create, true)['UserCreateForm'];
            for ($i = 0; $i < count($create['usernames']); $i++) {
                $model = new User();
                $model->username = $create['usernames'][$i];
                $model->email = $create['emails'][$i];
                $model->setPassword($create['passwords'][$i]);
                $model->generateAuthKey();
                $model->generateEmailVerificationToken();
                try {
                    if ($model->validate()) {
                        $model->save();
                    } else {
                        throw new \SoapFault("301", "Data didn't perform during validation");
                    }
                } catch (\Exception $e) {
                    throw new \SoapFault("500", "Failed inserting data");
                }
            }
        }
    }

    /**
     * @param string $auth_key
     * @param integer $user_id
     * @soap
     */
    public function deleteUser($auth_key, $user_id)
    {
        $verify = User::findOne(['auth_key' => $auth_key]);

        if (!empty($verify)) {
            try {
                User::find()->where(['id' => $user_id])->one()->delete();
            } catch (\Exception $e) {
                throw new \SoapFault("500", "Failed to delete user");
            }
        }
    }
}