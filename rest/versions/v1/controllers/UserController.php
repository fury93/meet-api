<?php

namespace rest\versions\v1\controllers;

use yii\rest\ActiveController;
use \Yii;

/**
 * Class UserController
 * @package rest\versions\v1\controllers
 */
class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

}
