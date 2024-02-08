<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property int $parent
 * @property int $position
 * @property int|null $folder_id
 * @property int|null $page_id
 *
 * @property Folders $folder
 * @property Pages $page
 *
 * @property Menu[] $items
 */
class Menu extends ActiveRecord
{
    public $items = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent', 'position'], 'required'],
            [['parent', 'position', 'folder_id', 'page_id'], 'integer'],
            [['folder_id'], 'exist', 'skipOnError' => true, 'targetClass' => Folders::class, 'targetAttribute' => ['folder_id' => 'id']],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pages::class, 'targetAttribute' => ['page_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent' => 'Parent',
            'position' => 'Position',
            'folder_id' => 'Folder ID',
            'page_id' => 'Page ID',
        ];
    }

    /**
     * Gets query for [[Folder]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFolder()
    {
        return $this->hasOne(Folders::class, ['id' => 'folder_id']);
    }

    /**
     * Gets query for [[Page]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Pages::class, ['id' => 'page_id']);
    }

    /**
     * @param $items static[]
     * @return array
     */
    public static function getItemsForNav($items)
    {
        $navItems = [];
        foreach ($items as $item) {
            if ($item->folder_id && $item->items) {
                $navItems[] = ['label' => $item->folder->title, 'items' => static::getItemsForNav($item->items)];
            }

            if ($item->page_id) {
                $navItems[] = ['label' => $item->page->title, 'url' => '/' . $item->page->url];
            }
        }

        return $navItems;
    }

    public static function getGroupedItems()
    {
        /** @var static[] $items */
        $items = static::find()->orderBy('position')->all();

        foreach ($items as $index => $item) {
            if ($item->parent === 0) {
                continue;
            }

            $parent = static::findParent($items, $item->parent);
            if ($parent) {
                ArrayHelper::remove($items, $index);
                $parent->items = array_merge($parent->items, [$item]);
            }
        }

        return $items;
    }

    /**
     * @param $items static[]
     * @param $id
     * @return static|null
     */
    protected static function findParent($items, $id)
    {
        foreach ($items as $item) {
            if ($item->id === $id) {
                return $item;
            }

            if ($find = static::findParent($item->items, $id)) {
                return $find;
            }
        }

        return null;
    }

    public static function createPageItem($pageId)
    {
        $item = new static();
        $item->parent = 0;
        $item->position = static::find()->max('position') + 1;
        $item->page_id = $pageId;
        $item->save();
    }

    public static function createFolderItem($title)
    {
        $folder = new Folders();
        $folder->title = $title;
        $folder->save();

        $item = new static();
        $item->parent = 0;
        $item->position = static::find()->max('position') + 1;
        $item->folder_id = $folder->id;
        $item->save();
    }
}
