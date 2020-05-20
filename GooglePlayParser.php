<?php

namespace app\commands\Parsers;

use DiDom\Document;
use GuzzleHttp\Client;
use Yii;
use yii\console\Controller;
use PHPExcel;
use PHPExcel_IOFactory;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GooglePlayController extends Controller
{
    public function actionGetData(string $url)
    {
        $client = new Client(['base_uri' => 'https://play.google.com']);
        $a = $url;
        $content = $client->get($a)->getBody();
        $document = new Document($content->getContents());
        $hrefs = $document->find('div.b8cIId.ReQCgd.Q9MA7b > a');

        $objPHPExcel = new PHPExcel();

        $emails = [];
        $key = 1;
        foreach ($hrefs as $href) {
            $link = 'https://play.google.com' . $href->getAttribute('href');
            $title = $href->first('div')->getAttribute('title');
            $appContent = $client->get($link)->getBody();
            $app = new Document($appContent->getContents());
            $email = $app->first('a[href^="mailto"]')->text();

            if (!in_array($email, $emails)) {
                $keyPlus = ++$key;
                $emails[] = $email;
                $objPHPExcel->getActiveSheet()
                    ->setCellValue('A' . $keyPlus, $email)
                    ->setCellValue('B' . $keyPlus, $link)
                    ->setCellValue('C' . $keyPlus, $title);
            }
            sleep(1);
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $path = 'web/docs/' . date('d.m.y.') . 'GooglePlay.xls';
        $objWriter->save($path);
    }
}
