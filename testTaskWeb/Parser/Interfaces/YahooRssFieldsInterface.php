<?php

namespace Parser\Interfaces;

use DiDom\Element;
use DOMElement;

interface YahooRssFieldsInterface
{
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const LINK = 'link';
    const PUB_DATE = 'pubDate';
    const SOURCE = 'source';
    const GUID = 'guid';
    const MEDIA_CONTENT = 'media:content';
    const MEDIA_TEXT = 'media:text';
    const MEDIA_CREDIT = 'media:credit';

    /**
     * @return Element[]|DOMElement[]
     */
    public function getTitle();

    /**
     * @return Element[]|DOMElement[]
     */
    public function getDescription();

    /**
     * @return Element[]|DOMElement[]
     */
    public function getLink();

    /**
     * @return Element[]|DOMElement[]
     */
    public function getPubDate();

    /**
     * @return Element[]|DOMElement[]
     */
    public function getSource();

    /**
     * @return Element[]|DOMElement[]
     */
    public function getGuid();

    /**
     * @param bool $extractFullImage
     * @return Element[]|DOMElement[]
     */
    public function getMediaContent($extractFullImage = true);

    /**
     * @return Element[]|DOMElement[]
     */
    public function getMediaText();

    /**
     * @return Element[]|DOMElement[]
     */
    public function getMediaCredit();
}