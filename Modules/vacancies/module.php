<?php

namespace app\modules\vacancies;
/**
 * modules module definition class
 */

use Yii;

class module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\vacancies\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\vacancies\commands';
        }
    }
}
