<?php

namespace app\controllers\admin;

use app\models\Folders;
use app\models\Menu;
use app\models\Pages;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * PagesController implements the CRUD actions for Pages model.
 */
class MenuController extends CommonController
{
    public $layout = 'admin';

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
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Pages models.
     *
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            if ($pageId = Yii::$app->request->post('page')) {
                Menu::createPageItem($pageId);
            }
            if ($folderTitle = Yii::$app->request->post('folder')) {
                Menu::createFolderItem($folderTitle);
            }

            return $this->redirect(['index']);
        }

        $menuItems = Menu::getGroupedItems();
        $unusedPages = Pages::getUnusedPages();

        return $this->render('index', [
            'menuItems' => $menuItems,
            'unusedPages' => $unusedPages,
        ]);
    }

    public function actionSort()
    {
        $parent = Yii::$app->request->post('parent');
        $ids = Yii::$app->request->post('items');

        if ($parent === null || empty($ids)) {
            return;
        }

        $position = 1;
        foreach ($ids as $id) {
            if ($item = Menu::findOne($id)) {
                $item->position = $position++;
                $item->parent = $parent;
                $item->save();
            }
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        if (($model = Menu::findOne(['id' => $id])) === null) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

        if ($model->folder_id && $folder = Folders::findOne(['id' => $model->folder_id])) {
            $folder->delete();
        }

        if ($children = Menu::findAll(['parent' => $id])) {
            foreach ($children as $child) {
                $this->actionDelete($child->id);
            }
        }

        $model->delete();

        return $this->redirect('index');
    }
}
