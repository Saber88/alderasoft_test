<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "pages".
 *
 * @property int $id
 * @property string $title
 * @property string $url
 * @property string|null $content
 *
 * @property Menu[] $menu
 */
class Pages extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'url'], 'required'],
            [['content'], 'string'],
            [['title', 'url'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'url' => 'Адрес',
            'content' => 'Содержание',
        ];
    }

    /**
     * Gets query for [[Menus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::class, ['page_id' => 'id']);
    }

    /**
     * @return static[]
     */
    public static function getUnusedPages()
    {
        return static::find()
            ->joinWith(['menu' => function (ActiveQuery $query) {
                $query->where(['parent' => null]);
            }])
            ->all();
    }

    public static function getVisiblePage($url)
    {
        $query = static::find()->where(['url' => $url]);

        if (Yii::$app->user->isGuest) {
            $query->joinWith(['menu' => function (ActiveQuery $query) {
                $query->andWhere(['IS NOT', 'parent', null]);
            }]);
        }

        return $query->one();
    }
}
