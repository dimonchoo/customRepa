<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\vacancies\commands;

use app\modules\vacancies\models\Progress;
use app\modules\vacancies\models\Vacancies;
use yii\console\Controller;
use GuzzleHttp\Client;

class ParserController extends Controller
{
    /**
     * По сколько выводить
     * @var int
     */
    public $startCount = 50;

    /**
     * Добавлять страниц по..
     * @var int
     */
    public $countByNumber = 50;

    public function actionRun()
    {
        $vacancion = new Vacancies;
        $progress = new Progress;
        $lastNumber = $progress->getLastStartCountNumber();
        if (!is_null($lastNumber)) {
            $this->startCount = $lastNumber->start_count_number;
        }
        while ($this->startCount !== false) {
            $jsonObject = $vacancion->getNavigationObjects(new Client, $this->startCount, $this->countByNumber);
            if ($jsonObject === false) {
                $this->startCount = false;
                break;
            }else{
                $vacancion->setObject($jsonObject)->addVacancies();
                $progress->addProgress($vacancion);
            }
            $this->stdout($this->startCount . PHP_EOL);
            $this->startCount += $this->countByNumber;
        }
    }
}