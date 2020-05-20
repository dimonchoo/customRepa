<?php

namespace app\controllers\noise\api\v1;

use app\models\Noise\Data;
use app\models\Noise\Settings;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\rest\ActiveController;
use yii\web\Response;

class DataController extends ActiveController
{
    public $modelClass = 'app\models\Noise\Data';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_XML;
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;

        $behaviors1 = [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                      'allow' => true,
                      'ips' => ['155.xxx.xxx.xxx']
                    ]
                ],
            ]
        ];

        return ArrayHelper::merge($behaviors, $behaviors1);
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

    public function actionCreate()
    {
        $settings = null;
        $post = \Yii::$app->request->post();
        if (array_key_exists('idDevice', $post)) {
            $settings = Settings::find()->where(['id_device' => $post['idDevice']])
                ->one();
        }
        $searchModel = new Data();
        if ($searchModel::getDb()->getDriverName() === 'mysql') {
            $searchModel->data = Json::encode($post);
            $searchModel->save();
        }else{
            $searchModel->data = $post;
            $searchModel->save();
        }
        return $settings;
    }

    protected function verbs()
    {
        return [
            'create' => ['POST']
        ];
    }
}
