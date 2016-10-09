<?php

namespace rest\versions\v1\controllers;

use common\models\Auth;
use common\models\User;
use yii\rest\ActiveController;
use \Yii;

class SiteController extends ActiveController
{
    public $modelClass = 'common\models\Auth';

    const TEMP_EMIL = 'test@meet.com';

    public function actions()
    {
        $actions = parent::actions();

        $actions['auth'] = [
            'class' => 'yii\authclient\AuthAction',
            'successCallback' => [$this, 'onAuthSuccess'],
        ];

        return $actions;
    }

    public function onAuthSuccess($client)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
        $attributes = $client->getUserAttributes();

        /* @var $auth Auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // авторизация
                $user = User::findIdentity($auth->user_id);
                Yii::$app->user->login($user);
            } else { // регистрация
                if (isset($attributes['email']) && User::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "Пользователь с такой электронной почтой как в {client} уже существует, но с ним не связан. Для начала войдите на сайт использую электронную почту, для того, что бы связать её.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new User([
                        'username' => $attributes['nickname'] || $attributes['first_name'] . ' ' . $attributes['last_name'],
                        'email' => isset($attributes['email']) ? $attributes['email'] : self::TEMP_EMIL, // TODO TEMP
                        'password' => $password,
                    ]);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();
                    $transaction = $user->getDb()->beginTransaction();
                    if ($user->save()) {
                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user);
                        } else {
                            print_r($auth->getErrors());
                        }
                    } else {
                        print_r($user->getErrors());
                    }
                }
            }
        } else { // Пользователь уже зарегистрирован
            if (!$auth) { // добавляем внешний сервис аутентификации
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }
        }
    }

    public function actionTest(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

        return $this->render('auth');
    }
}