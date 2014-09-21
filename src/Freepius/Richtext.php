<?php

namespace Freepius;

use Michelf\Markdown;
use Michelf\MarkdownExtra;
use Michelf\SmartyPants;
use Michelf\SmartyPantsTypographer;

/**
 * Transform a text using Markdown(Extra) and/or SmartyPants(Typographer)
 */
class Richtext implements RichtextInterface
{
    /**
     * @var array
     */
    protected $config;

    /* @var Markdown|MarkdownExtra */
    protected $_markdown;

    /* @var SmartyPants|SmartyPantsTypographer */
    protected $_smartypants;


    public function __construct(array $config = array())
    {
        $this->config = array_merge(static::DEFAULT_CONFIG, $config);

        if (
            null !== $this->config['locale'] &&
            null === $this->config['smartypants.attr'] &&
            null !== static::SMARTYPANTS_ATTR_BY_LOCALE[$this->config['locale']]
        ) {
            $this->config['smartypants.attr'] =
                static::SMARTYPANTS_ATTR_BY_LOCALE[$this->config['locale']];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function transform($text)
    {
        return $this->smartypants($this->markdown($text));
    }

    /**
     * {@inheritdoc}
     */
    public function markdown($text)
    {
        $md = $this->getMarkdown();

        // avoid conflicts for footnote ids
        if ($this->config['extra']) {
            $md->fn_id_prefix = uniqid();
        }

        $html = $md->transform($text);

        // remove <script> tags
        if ($this->config['remove.script.tags']) {
            $html = preg_replace(static::SCRIPT_TAG_PATTERN, '', $html);
        }

        return $html;
    }

    /**
     * {@inheritdoc}
     */
    public function smartypants($text)
    {
        return $this->getSmartypants()->transform($text);
    }

    /**
     * Initialize and return an instance of Markdown|MarkdownExtra class.
     */
    public function getMarkdown()
    {
        if (!$this->_markdown) {
            $this->_markdown = $this->config['extra'] ? new MarkdownExtra : new Markdown;
            $this->_markdown->empty_element_suffix = ">";
        }

        return $this->_markdown;
    }

    /**
     * Initialize and return an instance of SmartyPants|SmartyPantsTypographer class.
     */
    public function getSmartypants()
    {
        if (!$this->_smartypants) {
            // Typographer
            if ($this->config['typo']) {
                $this->_smartypants = new SmartyPantsTypographer($this->config['smartypants.attr']);

                if ('fr' === $this->config['locale']) {
                    // French quotes
                    $this->_smartypants->smart_doublequote_open  = '&#171;';
                    $this->_smartypants->smart_doublequote_close = '&#187;';
                }
            // Basic
            } else {
                $this->_smartypants = new SmartyPants($this->config['smartypants.attr']);
            }
        }

        return $this->_smartypants;
    }
}
