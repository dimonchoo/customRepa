<?php

namespace Parser\Core;

use DiDom\Element;
use Parser\Interfaces\RssReaderInterface;
use GuzzleHttp\Client;
use DiDom\Document;
use DiDom\Query;
use Parser\Core\RssFields;
use Parser\Core\ImageDownload;

class RssReader implements RssReaderInterface
{
    private $client;
    private $rssContent;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function setRssLink(string $link)
    {
        $this->rssContent = $this->client->get($link)->getBody()->getContents();
    }

    public function getItems(string $item = '//item')
    {
        if (is_null($this->rssContent)) {
            return false;
        }

        $doc = new Document($this->rssContent, false, 'UTF-8', Document::TYPE_XML);
        return $doc->find($item, Query::TYPE_XPATH);
    }

    public function getFields(Element $item)
    {
        return new RssFields($item);
    }

    public function downloadImage($img, $id)
    {
        return ImageDownload::downloadImage($img, $id);
    }
}