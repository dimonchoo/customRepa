<?php

namespace Parser\Interfaces;

use DiDom\Element;
use DOMElement;

interface RssReaderInterface
{
    /**
     * @param string $link
     * @return Element
     */
    public function setRssLink(string $link);

    /**
     * @param string $xpathItem
     * @return Element[]|DOMElement[]
     */
    public function getItems(string $xpathItem);

    /**
     * @param Element $item
     * @return Element
     */
    public function getFields(Element $item);
}