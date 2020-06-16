<?php

namespace app\modules\vacancies\models;

use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

class Progress extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->mpsv;
    }

    public static function tableName()
    {
        return 'progress';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @param Vacancies $navigation
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function addProgress(Vacancies $navigation)
    {
        $row = self::find()->where(new Expression('DATE(created_at) = CURRENT_DATE'))->one();
        if (is_null($row)) {
            $progress = new self;
            $progress->start_count_number = $navigation->getBody()['pagination']['start'];
            $progress->summary_objects = $navigation->getJsonObject()['count'];
            $progress->save();
        }else{
            $row->start_count_number = $navigation->getBody()['pagination']['start'];
            $row->summary_objects = $navigation->getJsonObject()['count'];
            $row->update();
        }
    }

    /**
     * @return Progress|array|ActiveRecord|null
     */
    public function getLastStartCountNumber()
    {
        return self::find()->where(new Expression('DATE(created_at) = CURRENT_DATE'))->one();
    }
}