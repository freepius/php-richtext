<?php

namespace Freepius;

interface RichtextInterface
{
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
