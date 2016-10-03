<?php

namespace rest\versions\v1;

use yii\base\Module;

class RestModule extends Module
{
    public $controllerNamespace = 'rest\versions\v1\controllers';

    public function init()
    {
        parent::init();
    }
}