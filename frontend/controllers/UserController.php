<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\UserCreateForm;
use frontend\models\UserForm;
use Yii;
use yii\grid\GridView;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\widgets\ActiveForm;

/**
 * Class UserController
 * @package frontend\controllers
 */
class UserController extends Controller
{
    /**
     * @var \mongosoft\soapclient\Client
     */
    public $client;

    /**
     * UserController constructor.
     * @param $id
     * @param $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

        if (!Yii::$app->session->get('auth_key')) {
            $this->redirect('site/index');
        }

        $this->client = new \mongosoft\soapclient\Client([
            'url' => 'http://soap-server.local.ew/index.php?r=api/soapApi',
        ]);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'Yii\web\ErrorAction',
            ]
        ];
    }

    /** GridView with all users (request from SOAP)
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = json_decode($this->client->getAllUsers(Yii::$app->session->get('auth_key')), true);
        $dataProvider = new ArrayDataProvider([
            'models' => $model
        ]);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**Returning a form to update a user
     *
     * @param $id
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isPost) {
            $this->client->updateUser(Yii::$app->session->get('auth_key'), $id, json_encode(Yii::$app->request->post('UserForm')));
            return $this->redirect('index.php?r=user/index');
        }
        $params = json_decode($this->client->getOneUser(Yii::$app->session->get('auth_key'), $id), true);
        $model = new UserForm();
        $model->setAttributes($params, false);

        return $this->render('update', [
            'model' => $model
        ]);
    }

    /** Returning a Dynamic ActiveForm to create users
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isPost) {
            $this->client->createUsers(Yii::$app->session->get('auth_key'), json_encode(Yii::$app->request->post()));
            return $this->redirect('index.php?r=user/index');
        }

        $model = [new UserCreateForm()];

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /** Action used with views/user/create to add dynamic forms
     *
     * @return string
     */
    public function actionForm()
    {
        return $this->renderAjax('form',[
            'form' => new ActiveForm(),
            'model' => new UserCreateForm(),
            'id' => Yii::$app->request->post('id')
        ]);
    }

    /** Action used to delete a user
     *
     * @return \yii\web\Response
     */
    public function actionDelete()
    {
        if (Yii::$app->request->isPost) {
            $this->client->deleteUser(Yii::$app->session->get('auth_key'), Yii::$app->request->get('id'));
        }
        return $this->redirect('index.php?r=user/index');

    }
}