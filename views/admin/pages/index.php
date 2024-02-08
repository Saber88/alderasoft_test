<?php

use app\models\Pages;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\PagesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать страницу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'title',
            [
                'attribute' => 'url',
                'value' => function (Pages $model) {
                    return Url::toRoute(['/' . $model->url], true);
                }
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Pages $model) {
                    if ($action === 'view') {
                        return Url::toRoute(['/' . $model->url]);
                    }
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'buttons' => [
                    'view' => function ($url) {
                        return Html::a((new ActionColumn)->icons['eye-open'], $url, ['title' => Yii::t('yii', 'View'), 'target' => '_blank', 'data-pjax' => 0]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
