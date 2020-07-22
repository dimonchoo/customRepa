<?php

namespace Parser\Core;

use DiDom\Element;
use Parser\Interfaces\YahooRssFieldsInterface;
use DiDom\Query;

class RssFields implements YahooRssFieldsInterface
{
    private $item;

    public function __construct(Element $item)
    {
        $this->item = $item;
    }

    public function getTitle()
    {
        if ($this->item->has(self::TITLE)) {
            return $this->item->first(self::TITLE);
        }
        return Element::create(self::TITLE, null);
    }

    public function getDescription()
    {
        if ($this->item->has(self::DESCRIPTION)) {
            return $this->item->first(self::DESCRIPTION);
        }
        return Element::create(self::DESCRIPTION, null);
    }

    public function getLink()
    {
        if ($this->item->has(self::LINK)) {
            return $this->item->first(self::LINK);
        }
        return Element::create(self::LINK, null);
    }

    public function getPubDate()
    {
        if ($this->item->has(self::PUB_DATE)) {
            return $this->item->first(self::PUB_DATE);
        }
        return Element::create(self::PUB_DATE, null);
    }

    public function getSource()
    {
        if ($this->item->has(self::SOURCE)) {
            return $this->item->first(self::SOURCE);
        }
        return Element::create(self::SOURCE, null);
    }

    public function getGuid()
    {
        if ($this->item->has(self::GUID)) {
            return $this->item->first(self::GUID);
        }
        return Element::create(self::GUID, null);
    }

    public function getMediaContent($extractFullImage = true)
    {
        if ($this->item->has(self::MEDIA_CONTENT, Query::TYPE_XPATH)) {
            $element = $this->item->first(self::MEDIA_CONTENT, Query::TYPE_XPATH);
            if ($extractFullImage) {
                $fullImage = explode('http', $element->getAttribute('url'));
                if (array_key_exists(2, $fullImage)) {
                    return $element->setAttribute('url', 'http' . $fullImage[2]);
                }else{
                    return $element;
                }
            } else{
                return $element;
            }
        }
        return Element::create(self::MEDIA_CONTENT, null);
    }

    public function getMediaText()
    {
        if ($this->item->has(self::MEDIA_TEXT, Query::TYPE_XPATH)) {
            return $this->item->first(self::MEDIA_TEXT, Query::TYPE_XPATH);
        }
        return Element::create(self::MEDIA_TEXT, null);
    }

    public function getMediaCredit()
    {
        if ($this->item->has(self::MEDIA_CREDIT, Query::TYPE_XPATH)) {
            return $this->item->first(self::MEDIA_CREDIT, Query::TYPE_XPATH);
        }
        return Element::create(self::MEDIA_CREDIT, null);
    }

}