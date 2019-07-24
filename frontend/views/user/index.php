<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $model yii\data\ActiveDataProvider */

$this->title = 'Users';

?>
<div class="user-index">
    <h1> <?= \yii\helpers\Html::encode($this->title) ?></h1>
    <a href="<?= \yii\helpers\Url::to(['user/create']) ?>" class="btn btn-success">Add users</a>
</div>

<?php
echo GridView::widget([
    'dataProvider' => $model,
    'layout' => "{summary}\n{items}\n{pager}",
    'pager' => [
        'class' => 'yii\widgets\LinkPager',
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'username',
        'email',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Actions',
            'buttons' => [
                'view' => function($url, $model) {
                    return;
                },
                'update' => function ($url, $model) {
                    $url = Url::to(['user/update', 'id' => $model['id']]);
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => 'update']);
                },
                'delete' => function ($url, $model) {
                    $url = Url::to(['user/delete', 'id' =>  $model['id']]);
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => 'delete',
                        'data-confirm' => 'Are you sure you want to delete this item?',
                        'data-method' => 'post',
                    ]);
                },
            ],
        ],
    ],
]); ?>
