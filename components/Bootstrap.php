<?php
namespace app\components;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        \Yii::$container->setSingleton('LeadService');
    }
}
