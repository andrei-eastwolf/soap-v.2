<?php

/* @var $this yii\web\View */
/* @var $ping frontend\controllers\SiteController */
/* @var $client SoapClient */

$this->title = 'My Yii Application';

?>
<div class="site-index">

    <div class="body-content">
        <h1>Auth key: <?= Yii::$app->session->get('auth_key') ?></h1>
        <h2><?php if (isset($data['ping'])) {
                echo $data['ping'];
            } else if (isset($data['login'])) {
                if ($data['login']) {
                    echo "Succesfully logged in.";
                } else {
                    echo "Could not log in. Invalid credentials!";
                }
            } else if (isset($data['ping_auth'])) {
                \yii\helpers\VarDumper::dump($data['ping_auth']);
            }
            ?></h2>

        <?php $form = \yii\widgets\ActiveForm::begin() ?>


            <?php if (!Yii::$app->session->get('auth_key')) { ?>

            <?= \yii\helpers\Html::submitButton('Ping (without authentication)', ['name' => 'submit', 'value' => 'ping', 'class' => 'btn btn-info', 'id' => 'ping']) ?>
            <br><br>
            <label for="username" class="control-label">Username: </label>
            <input type="text" name="username" class="form-control" id="username"/>
            <label for="password" class="control-label">Password: </label>
            <input type="password" name="password" class="form-control" id="password"/>
            <br>

            <?= \yii\helpers\Html::submitButton('Login', ['name' => 'submit', 'value' => 'login', 'class' => 'btn btn-info', 'id' => 'login']) ?>
            <?php } else { ?>

            <?= \yii\helpers\Html::submitButton('Ping auth', ['name' => 'submit', 'value' => 'ping_auth', 'class' => 'btn btn-info', 'id' => 'ping_auth']) ?>

            <?php } ?>
        <?php \yii\widgets\ActiveForm::end() ?>
    </div>
</div>