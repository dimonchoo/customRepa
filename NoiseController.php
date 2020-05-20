<?php

namespace app\commands\Noise;

use app\models\Mail;
use app\models\Noise\Data;
use Yii;
use yii\console\Controller;

/**
 * Class NoiseController
 * Класс, который обрабатывает запросы из шумомеров.
 * Если какой-то шумомер превышает указанный уровень ДБ, идет отправка данных
 * с какого устройства и соответственно с какого апартамента.
 *
 * Команда должна быть запущена в супервизоре.
 *
 * @author dimonchoo
 * @package app\commands\Noise
 */
class NoiseController extends Controller
{
    // Интервал времени после последней отправки на имейл
    const PAUSE_INTERVAL = 5;
    const EMAIL_TO = 'info@domain.ru';

    /**
     * С интервалом в 1 минуту - проверяем превышение шума.
     * @return void
     */
    public function actionProcess() : void
    {
        $timePauseInterval = [];
        while (true) {
            $devices = Data::getDevicesByLastMinute();
            foreach ($devices as $device) {
                if ($device['count'] >= 60) {
                    if (array_key_exists('device', $timePauseInterval)) {
                        $this->stdout('Массив device существует' . PHP_EOL);
                        if (array_key_exists($device['device'], $timePauseInterval['device'])) {
                            $this->stdout('device существует в массиве, которых запомнили' . PHP_EOL);
                            $tsForDevice = $timePauseInterval['device'][$device['device']]['ts'];
                            $this->stdout('device время - ' . $tsForDevice . PHP_EOL);
                            if (time() > $tsForDevice) {
                                $this->stdout('device время' . time() > $tsForDevice . PHP_EOL);
                                $this->sendNotification($device);
                                // Через 5 минут
                                $timePauseInterval['device'][$device['device']]['ts'] = time() + (60 * self::PAUSE_INTERVAL);
                            }
                        } else {
                            $this->stdout('device там где не запомнили' . PHP_EOL);
                            $this->sendNotification($device);
                            // Через 5 минут
                            $timePauseInterval['device'][$device['device']]['ts'] = time() + (60 * self::PAUSE_INTERVAL);
                        }
                    } else {
                        $this->stdout('device нет в массиве с временем' . PHP_EOL);
                        $this->sendNotification($device);
                        // Через 5 минут
                        $timePauseInterval['device'][$device['device']]['ts'] = time() + (60 * self::PAUSE_INTERVAL);
                    }
                }
            }
            sleep(1);
        }
    }

    /**
     * Отправка e-mail о шуме в апартаментах
     * @return void
     */
    public function sendNotification(array $device) : void
    {
        Mail::sendPiNotification([
            'subject' => 'Превышение шума',
            'body' => 'Превышение шума из устройства ' . $device['device'],
            'setTo' => self::EMAIL_TO,
            'setName' => 'Шумомер ' . $device['device'],
            'setFrom' => 'info@domain.ru'
        ]);
    }
}
