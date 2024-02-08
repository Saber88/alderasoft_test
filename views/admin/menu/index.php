<?php

use kartik\sortable\Sortable;
use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var \app\models\Menu[] $menuItems */
/** @var \app\models\Pages[] $unusedPages */

$title = 'Структура меню';
$this->title = $title;

/**
 * @param $menuItems \app\models\Menu[]
 */
function getMenuItemsForSort($menuItems)
{
    $sortItems = [];
    foreach ($menuItems as $item) {
        if ($item->folder_id) {
            $sort = Sortable::widget([
                'items' => getMenuItemsForSort($item->items),
                'connected' => true,
                'options' => ['data-parent' => $item->id],
                'pluginEvents' => [
                    'sortupdate' => 'function() {saveSort(this);}',
                ]
            ]);
            $sortItems[] = [
                'content' => FAS::icon('folder') . ' '
                    . $item->folder->title . ' '
                    . Html::a(FAS::icon('trash'), ['delete', 'id' => $item->id], ['class' => 'float-end', 'title' => 'Удалить из меню', 'data-confirm' => 'Действительно удалить из меню?', 'data-method' => 'post']) . ' '
                    . $sort,
                'options' => ['data-id' => $item->id, 'class' => 'alert alert-warning']
            ];
        }

        if ($item->page_id) {
            $sortItems[] = [
                'content' => FAS::icon('file') . ' '
                    . $item->page->title . ' '
                    . Html::a(FAS::icon('trash'), ['delete', 'id' => $item->id], ['class' => 'float-end', 'title' => 'Удалить из меню', 'data-confirm' => 'Действительно удалить из меню?', 'data-method' => 'post']) . ' '
                    . Html::a(FAS::icon('pen'), ['/admin/pages/update', 'id' => $item->page_id], ['class' => 'float-end me-1']),
                'options' => ['data-id' => $item->id, 'class' => 'alert alert-primary']
            ];
        }
    }
    return $sortItems;
}

$js = <<<JS
    function saveSort(sort) {
        sort = $(sort);
        
        let items = [];
        sort.children("li").each(function () {
            items.push($(this).data("id"))
        });
        
        $.post({
            url: "/admin/menu/sort",
            data: {
                parent: sort.data("parent"),
                items: items
            }
        });
    }
JS;
$this->registerJs($js);

?>

<h1><?= $title ?></h1>
<br>

<div class="row">
    <div class="col-sm-6">

        <?= Sortable::widget([
            'items' => getMenuItemsForSort($menuItems),
            'connected' => true,
            'options' => ['data-parent' => 0],
            'pluginEvents' => [
                'sortupdate' => 'function() {saveSort(this);}',
            ]
        ]) ?>
    </div>
    <div class="col-sm-6">
        <h5>Добавить в меню</h5>
        <br>
        <?= Html::beginForm() ?>
        <?= Html::dropDownList('page', null, ArrayHelper::map($unusedPages, 'id', 'title'), ['class' => 'form-select mb-3', 'prompt' => '', 'required' => true]) ?>
        <?= Html::submitButton('Добавить страницу', ['class' => 'btn btn-success']) ?>
        <?= Html::endForm() ?>
        <br>
        <br>
        <?= Html::beginForm() ?>
        <?= Html::textInput('folder', null, ['class' => 'form-control mb-3', 'required' => true]) ?>
        <?= Html::submitButton('Добавить папку', ['class' => 'btn btn-success']) ?>
        <?= Html::endForm() ?>
    </div>
</div>