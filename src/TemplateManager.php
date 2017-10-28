<?php

class TemplateManager
{
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        $APPLICATION_CONTEXT = ApplicationContext::getInstance();

        $text = $this->replaceQuoteData($text, $data);
        $text = $this->replaceUserData($text, $data, $APPLICATION_CONTEXT);

        return $text;
    }

    /**
     * Replace quote data in text
     * @param $text
     * @param $data
     * @return mixed
     */
    private function replaceQuoteData($text, $data)
    {

        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;

        if ($quote) {

            $_quoteFromRepository = QuoteRepository::getInstance()->getById($quote->id);
            $usefulObject = SiteRepository::getInstance()->getById($quote->siteId);
            $destination = DestinationRepository::getInstance()->getById($quote->destinationId);

            $text = str_replace('[quote:summary_html]', Quote::renderHtml($_quoteFromRepository), $text);
            $text = str_replace('[quote:summary]', Quote::renderText($_quoteFromRepository), $text);
            $text = str_replace('[quote:destination_name]', $destination->countryName, $text);
            $text = str_replace('[quote:destination_link]',
                $usefulObject->url . '/' . $destination->countryName . '/quote/' . $_quoteFromRepository->id, $text);
        }

        return $text;
    }

    /**
     * Replace user data in text
     * @param $text
     * @param array $data
     * @param $APPLICATION_CONTEXT
     * @return mixed
     */
    private function replaceUserData($text, array $data, $APPLICATION_CONTEXT)
    {
        $_user = (isset($data['user']) and ($data['user'] instanceof User)) ? $data['user'] : $APPLICATION_CONTEXT->getCurrentUser();
        if ($_user) {
            (strpos($text, '[user:first_name]') !== false) and $text = str_replace('[user:first_name]',
                ucfirst(mb_strtolower($_user->firstname)), $text);
        }
        return $text;
    }
}
