<?php

namespace Freepius;

interface RichtextInterface
{
    /**
     * Define the better SmartyPants(Typographer) attributes for a given locale
     */
    const SMARTYPANTS_ATTR_BY_LOCALE = array
    (
        'fr' => 'qgD:+;+m+h+H+f+u+t',
    );

    /**
     * Locales handled by the SMARTYPANTS_ATTR_BY_LOCALE const.
     */
    const HANDLED_LOCALES = array('fr');

    /**
     * Default: active the *MarkdownExtra* and *SmartyPantsTypographer* parsers
     */
    const DEFAULT_CONFIG = array
    (
        'locale'             => null,
        'extra'              => true,
        'typo'               => true,
        'smartypants.attr'   => 2, // ie: SMARTYPANTS_ATTR_LONG_EM_DASH_SHORT_EN,
        'remove.script.tags' => true,
    );

    /**
     * Pattern to recognize the <script> tags.
     */
    const SCRIPT_TAG_PATTERN = '{<(\s*)script(.*)>.*<(\s*)/(\s*)script(.*)>}si';

    /**
     * Apply on a text both markdown and smartypants parsers.
     */
    public function transform($text);

    /**
     * Htmlify a text with the markdown parser.
     */
    public function markdown($text);

    /**
     * Transform a text with the smartypants parser.
     */
    public function smartypants($text);

    /**
     * Return the used Markdown(Extra) instance, allowing user to modify
     * its public configuration variables.
     *
     * @return Markdown|MarkdownExtra
     */
    public function getMarkdown();

    /**
     * Return the used SmartyPants(Typographer) instance, allowing user to modify
     * its public configuration variables.
     *
     * @return SmartyPants|SmartyPantsTypographer
     */
    public function getSmartypants();
}
