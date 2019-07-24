<?php

use Yii;

/* @var $form \yii\widgets\ActiveForm */
/* @var $model \frontend\models\UserCreateForm */
/* @var $id integer */

if (Yii::$app->request->isAjax) {
    echo "<div class='panel panel-info'>";
        echo "<div class='panel-body'>";
            echo $form->field($model, 'usernames[' . $id . ']')->textInput();
            echo $form->field($model, 'emails[' . $id . ']')->input('email');
            echo $form->field($model, 'passwords[' . $id . ']')->passwordInput();
        echo "</div>";
    echo "</div>";
}