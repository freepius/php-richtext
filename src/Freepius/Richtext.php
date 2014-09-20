<?php

namespace Freepius;

use Michelf\Markdown;
use Michelf\MarkdownExtra;
use Michelf\SmartyPants;
use Michelf\SmartyPantsTypographer;

/**
 * Transform a text using Markdown(Extra) and/or SmartyPants(Typographer)
 */
class RichText implements RichTextInterface
{
    const SCRIPT_TAG_PATTERN = '{<(\s*)script(.*)>.*<(\s*)/(\s*)script(.*)>}si';

    const DEFAULT_SMARTYPANTS_OPTIONS = 'qgD';

    const DEFAULT_SMARTYPANTS_TYPO_OPTIONS = 'qgD:+;+m+h+H+f+u+t';

    public $markdown;
    public $smartypants;

    public function __construct(array $options = array())
    {
        $this->markdown        = new Markdown();
        $this->markdownExtra   = new MarkdownExtra();
        $this->smartypants     = new SmartyPantsTypographer(DEFAULT_SMARTYPANTS_OPTIONS);
        $this->smartypantsTypo = new SmartyPants(DEFAULT_SMARTYPANTS_TYPO_OPTIONS);

        // HTML output
        $this->markdown->empty_element_suffix      = ">";
        $this->markdownExtra->empty_element_suffix = ">";

        if (isset($options['locale'] && $options['locale'] === 'fr')
        {
            // French quotes
            $this->smartypantsTypo->smart_doublequote_open  = '&#171;';
            $this->smartypantsTypo->smart_doublequote_close = '&#187;';
        }
    }

    public function full($text)
    {
        $text = $this->markdown_extra($text);

        return $this->smartypants_typo($text);
    }

    public function markdown($text)
    {
        return $this->markdown->transform($text);
    }

    public function markdown_extra($text)
    {
        // avoid conflicts for footnote ids
        $this->markdownExtra->fn_id_prefix = uniqid();

        return $this->markdownExtra->transform($text);
    }

    public function smartypants($text)
    {
        return $this->smartypants->transform($text);
    }

    public function smartypants_typo($text)
    {
        return $this->smartypantsTypo->transform($text);
    }
}
