<?php
require '../vendor/autoload.php';

use Parser\Core\RssReader;
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '172.17.0.2',
    'database' => 'wizards',
    'username' => 'root',
    'password' => '123654',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$reader = new RssReader();
$rssLinks = [
    'news' => 'https://finance.yahoo.com/rss/',
    'entertainment' => 'https://news.yahoo.com/rss/entertainment'
];

foreach ($rssLinks as $category => $rssLink) {

    $reader->setRssLink($rssLink);
    foreach ($reader->getItems() as $item) {
        $field = $reader->getFields($item);

        $isExistPost = Capsule::table('yahoo_posts')
            ->where('link', '=', $field->getLink()->text())
            ->exists();

        if (!$isExistPost) {
            $insertedId = Capsule::table('yahoo_posts')->insertGetId([
                'title' => $field->getTitle()->text(),
                'description' => $field->getDescription()->text(),
                'link' => $field->getLink()->text(),
                'pub_date' => $field->getPubDate()->text(),
                'source' => $field->getSource()->getAttribute('url'),
                'guid' => $field->getGuid()->text(),
                'media_content' => $field->getMediaContent()->getAttribute('url'),
                'media_credit' => $field->getMediaCredit()->getAttribute('role'),
                'media_text' => $field->getMediaText()->text(),
                'category' => $category
            ]);

//            $reader->downloadImage($field->getMediaContent()->getAttribute('url'), $insertedId);
        }
    }

}