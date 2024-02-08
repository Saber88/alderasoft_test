<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Pages $model */

$this->title = $model->isNewRecord ? 'Создать страницу' : $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/js/ckeditor/ckeditor.js');

$token = Yii::$app->request->csrfToken;
$js = <<<JS
    $("#pages-title").on("change", function () {
        let converter = {
            'а': 'a',    'б': 'b',    'в': 'v',    'г': 'g',    'д': 'd',
            'е': 'e',    'ё': 'e',    'ж': 'zh',   'з': 'z',    'и': 'i',
            'й': 'y',    'к': 'k',    'л': 'l',    'м': 'm',    'н': 'n',
            'о': 'o',    'п': 'p',    'р': 'r',    'с': 's',    'т': 't',
            'у': 'u',    'ф': 'f',    'х': 'h',    'ц': 'c',    'ч': 'ch',
            'ш': 'sh',   'щ': 'sch',  'ь': '',     'ы': 'y',    'ъ': '',
            'э': 'e',    'ю': 'yu',   'я': 'ya'
        };
        
        let title = $(this).val().toLowerCase();
        let url = '';
        for (let i = 0; i < title.length; ++i) {
            if (converter[title[i]] === undefined){
                url += title[i];
            } else {
                url += converter[title[i]];
            }
        }
 
        url = url.replace(/[^-0-9a-z]/g, '-');
        url = url.replace(/[-]+/g, '-');
        url = url.replace(/^\-|-$/g, ''); 
        
        $("#pages-url").val(url);
    });

    ClassicEditor.create(document.querySelector("#pages-content"), {
        height: 500,
        simpleUpload: {
            uploadUrl: "/admin/upload/image",
            headers: {
                "X-CSRF-TOKEN": "$token"
            }
        }
    });
JS;
$this->registerJs($js);

?>
<div class="pages-edit">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="pages-form">

        <?php $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-form-label col-2 text-xl-end',
                    'offset' => 'offset-2',
                    'wrapper' => 'col-xl-10 col-xxl-9',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ]); ?>

        <?= $form->field($model, 'title')->textInput(['autofocus' => true, 'disabled' => ($model->title === 'Главная')]) ?>

        <?= $form->field($model, 'url', ['template' => "{label}\n{beginWrapper}
            <div class='input-group'>
                <span class='input-group-text'>" . Url::base(true) . "/</span>
                {input}
            </div>\n{error}\n{endWrapper}"])->textInput(['disabled' => ($model->title === 'Главная')]) ?>

        <?= $form->field($model, 'content')->textarea() ?>

        <div class="form-group">
            <div class="offset-xl-2">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                <?= Html::a('Назад', ['index'], ['class' => 'btn btn-outline-secondary', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
