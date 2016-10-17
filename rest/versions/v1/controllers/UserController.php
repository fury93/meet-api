<?php

namespace rest\versions\v1\controllers;

use common\models\UserForm;
use rest\versions\v1\helper\ResponseHelper;
use yii\rest\ActiveController;
use \Yii;
use yii\web\NotFoundHttpException;

/**
 * Class UserController
 * @package rest\versions\v1\controllers
 */
class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

    /**
     * @return mixed
     */
    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['update']);
        unset($actions['create']);
        unset($actions['delete']);

        return $actions;
    }

    /**
     * Prepare data for index
     *
     * @return array
     */
    public function prepareDataProvider()
    {
        $users = UserForm::getUsersList();

        return ResponseHelper::success($users);
    }

    /**
     * Register user
     *
     * @return UserForm|string
     */
    public function actionCreate()
    {
        $model = new UserForm();

        $params = \Yii::$app->getRequest()->getBodyParams();

        if ($model->load($params, '') && $model->createUser()) {
            return ResponseHelper::success(UserForm::filterUserData($model));
        }

        return ResponseHelper::failed($model->getErrors());
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);
        $params = \Yii::$app->getRequest()->getBodyParams();

        if ($model->load($params, '') && $model->updateUser()) {
            return ResponseHelper::success(UserForm::filterUserData($model));
        }

        return ResponseHelper::failed($model->getErrors());
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model) {
            return ResponseHelper::success($model);
        } else {
            return ResponseHelper::failed(null);
        }
    }

    /**
     * Deletes an existing User model.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return ResponseHelper::success(['id' => $id]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return UserForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
