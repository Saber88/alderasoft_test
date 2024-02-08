<?php

use app\models\Menu;
use kartik\nav\NavX;
use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;

NavBar::begin([
    'brandLabel' => FAS::i('home'),
    'brandUrl' => Yii::$app->homeUrl,
    'options' => ['class' => 'navbar-expand-md'],
]);

echo NavX::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => Menu::getItemsForNav(Menu::getGroupedItems()),
]);

if (Yii::$app->user->isGuest) {
    echo Html::tag('div', Html::a('Login', ['/site/login'], ['class' => ['nav-link']]), ['class' => ['ms-auto nav-item p-2']]);
    echo Html::tag('div', Html::a('Signup', ['/site/signup'], ['class' => ['nav-link']]), ['class' => ['nav-item p-2']]);
} else {
    if (Yii::$app->user->identity->isAdmin()) {
        echo Html::tag('div', Html::a('Administration', ['/admin'], ['class' => ['nav-link']]), ['class' => ['ms-auto nav-item p-2']]);
    }

    echo Html::beginForm(['/site/logout'], 'post', ['class' => Yii::$app->user->identity->isAdmin() ? 'nav-item p-2' : 'ms-auto nav-item p-2 '])
        . Html::submitButton(
            'Logout (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link nav-link border-0']
        )
        . Html::endForm();
}

NavBar::end();
