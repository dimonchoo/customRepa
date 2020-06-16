<?php

namespace app\modules\vacancies\models;

use Exception;
use GuzzleHttp\Client;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use app\components\Ignore;
use app\traits\mpsv\RequestParamsTrait;

class Vacancies extends ActiveRecord
{
    use RequestParamsTrait;
    private $jsonObject = null;
    private $navigationUrl = 'https://www.mpsv.cz/volna-mista/rest/volna-mista/query';

    public static function getDb()
    {
        return \Yii::$app->mpsv;
    }

    public static function tableName()
    {
        return 'object';
    }

    public function behaviors()
    {
        return [
            Ignore::className(),
        ];
    }

    /**
     * @param Client $client
     * @param int $start_count
     * @param int $count
     * @return bool|string
     */
    public function getNavigationObjects(Client $client, int $start_count, int $count)
    {
        $result = $client->post($this->navigationUrl,
            $this->setStartCount($start_count, $count)->options
        )->getBody()->getContents();

        if (empty(json_decode($result, true)['list'])) {
            return false;
        }
        return $result;
    }

    /**
     * @param string $jsonObject
     * @return $this
     */
    public function setObject(string $jsonObject)
    {
        $this->jsonObject = json_decode($jsonObject, true);
        return $this;
    }

    /**
     * @return null|array
     */
    public function getJsonObject()
    {
        return $this->jsonObject;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function addVacancies()
    {
        if (is_null($this->jsonObject)) {
            throw new Exception('Для обработки требуется указание объекта');
        }
        $formedArray = [];
        foreach ($this->jsonObject['list'] as $object) {
                array_push($formedArray, [$object, $object['id']]);
        }
        $this->upsertConflict(['object', 'site_id'], $formedArray);
    }

    /**
     * @param int $start_count
     * @param int $count
     * @return $this
     */
    public function setStartCount(int $start_count, int $count)
    {
        $this->body['pagination']['start'] = $start_count;
        $this->body['pagination']['count'] = $count;
        $this->options['json'] = $this->body;
        return $this;
    }
}