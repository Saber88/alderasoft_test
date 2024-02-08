<?php

use app\models\Menu;
use yii\helpers\Html;

$navItems = Menu::getItemsForNav(Menu::getGroupedItems());

?>
<div class="container">
    <div class="row">
        <div class="col-4"></div>

        <div class="col-4">
            <ul class="nav flex-column">
                <?php foreach ($navItems as $navItem): ?>

                    <?php if (isset($navItem['url'])): ?>
                        <li class="nav-item mt-3 fs-4">
                            <?= Html::a($navItem['label'], $navItem['url'], ['class' => 'nav-link fw-bold']) ?>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($navItem['items'])): ?>
                        <li class="nav-item mt-3 fs-4">
                            <div class="nav-link fw-bold disabled"><?= $navItem['label'] ?></div>
                        </li>
                        <?php foreach ($navItem['items'] as $subNavItem): ?>
                            <?php if (isset($subNavItem['url'])): ?>
                                <li class="nav-item">
                                    <?= Html::a($subNavItem['label'], $subNavItem['url'], ['class' => 'nav-link py-1']) ?>
                                </li>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php endif; ?>

                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-4"></div>
    </div>
</div>