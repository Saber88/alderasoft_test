<?php

namespace app\controllers\admin;

use app\models\UploadForm;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\UploadedFile;

class UploadController extends CommonController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'image' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * @return string
     */
    public function actionImage()
    {
        $model = new UploadForm();

        $model->files = UploadedFile::getInstancesByName('upload');
        if ($model->upload()) {
            return Json::encode(['url' => '/uploads/' . $model->files[0]->name]);
        }

        return null;
    }
}
