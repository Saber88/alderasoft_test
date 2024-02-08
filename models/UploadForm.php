<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $files;

    public function rules()
    {
        return [
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 4],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->files as $file) {
                $file->name = Yii::$app->security->generateRandomString() . '.' . $file->extension;
                $file->saveAs(Yii::getAlias('@webroot/uploads/') . $file->name);
                //            Image::getImagine()
//                ->open($filepath)
//                ->thumbnail(new Box(1000, 1000))
//                ->save($filepath, ['quality' => 90]);
            }
            return true;
        } else {
            return false;
        }
    }
}