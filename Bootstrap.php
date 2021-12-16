<?php

namespace petersonsilva\easyiigii;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if ($app->hasModule('gii')) {
            if (!isset($app->getModule('gii')->generators['easyii-gii'])) {
                $app->getModule('gii')->generators['easyii-gii-model'] = 'petersonsilva\easyiigii\model\Generator';
                $app->getModule('gii')->generators['easyii-gii-crud']['class'] = 'petersonsilva\easyiigii\crud\Generator';
                $app->getModule('gii')->generators['easyii-gii-migration'] = 'petersonsilva\easyiigii\migration\Generator';
            }
        }
    }
}
