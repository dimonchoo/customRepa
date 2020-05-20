<?php

namespace app\models\Noise;

use Yii;
use yii\db\ActiveRecord;


class Data extends ActiveRecord
{
    public function rules()
    {
        return [
            ['data', 'safe'],
        ];
    }

    public static function getDb()
    {
        return Yii::$app->noise;
    }

    public static function tableName()
    {
        return 'data';
    }

    /**
     * Получаем устройства за последнюю минуту
     * @return mixed
     */
    public static function getDevicesByLastMinute()
    {
        return Yii::$app->noise->createCommand("
            SELECT count(data), data->>'idDevice' as device
            FROM data
                WHERE ts > current_timestamp - INTERVAL '1 MINUTE'
            GROUP BY data->>'idDevice' ")->queryAll();
    }
}
