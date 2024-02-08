<?php

/** @var $page \app\models\Pages */

$this->registerJsFile('/js/ckeditor/ckeditor.js');

?>

<h2><?= $page->title ?></h2>

<div class="ck-content">
    <?= $page->content ?>
</div>
