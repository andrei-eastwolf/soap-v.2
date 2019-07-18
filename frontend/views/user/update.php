<?php

use Yii;

/* @var $model \common\models\User */

?>

<div id="user-update">
    <?php $form = \yii\widgets\ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= \yii\helpers\Html::submitButton('Save', ['name' => 'submit', 'value' => 'update', 'class' => 'btn btn-success']) ?>

    <?php \yii\widgets\ActiveForm::end(); ?>
</div>
