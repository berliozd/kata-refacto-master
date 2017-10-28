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

            $quote = QuoteRepository::getInstance()->getById($quote->id);
            $quoteRenderer = QuoteRenderer::getInstance();
            $quoteRenderer->setQuote($quote);

            $text = str_replace('[quote:summary_html]', $quoteRenderer->summary_html(), $text);
            $text = str_replace('[quote:summary]', $quoteRenderer->summary(), $text);
            $text = str_replace('[quote:destination_name]', $quoteRenderer->destination_name(), $text);
            $text = str_replace('[quote:destination_link]', $quoteRenderer->destination_link(), $text);
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
        $user = (isset($data['user']) and ($data['user'] instanceof User)) ? $data['user'] : $APPLICATION_CONTEXT->getCurrentUser();

        if ($user) {
            $userRenderer = UserRenderer::getInstance();
            $userRenderer->setUser($user);

            $text = str_replace('[user:first_name]', $userRenderer->first_name(), $text);
        }

        return $text;
    }
}
