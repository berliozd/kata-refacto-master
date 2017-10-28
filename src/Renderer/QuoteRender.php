<?php

/**
 * Created by PhpStorm.
 * User: didierberlioz
 * Date: 29/10/2017
 * Time: 00:22
 */
class QuoteRender
{

    use SingletonTrait;

    private $quote;

    public function summary_html()
    {
        return Quote::renderHtml($this->quote);
    }

    public function summary()
    {
        return Quote::renderText($this->quote);
    }

    public function destination_name()
    {
        $destination = DestinationRepository::getInstance()->getById($this->quote->destinationId);
        return $destination->countryName;
    }

    public function destination_link()
    {
        $destination = DestinationRepository::getInstance()->getById($this->quote->destinationId);
        $usefulObject = SiteRepository::getInstance()->getById($this->quote->siteId);

        return $usefulObject->url . '/' . $destination->countryName . '/quote/' . $this->quote->id;
    }

    /**
     * @param mixed $quote
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;
    }
}