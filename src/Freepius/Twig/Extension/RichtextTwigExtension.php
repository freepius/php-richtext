<?php

namespace Freepius\Twig\Extension;

use Freepius\RichtextInterface;

class RichtextTwigExtension extends \Twig_Extension
{
    protected $richtext;

    /**
     * Public constructor
     *
     * @param RichtextInterface $richtext
     */
    public function __construct(RichtextInterface $richtext)
    {
        $this->richtext = $richtext;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        $htmlSafe = array('is_safe' => array('html'));

        return array(
            'richtext'         => new \Twig_SimpleFilter($this->richtext, 'full'          , $htmlSafe),
            'markdown'         => new \Twig_SimpleFilter($this->richtext, 'markdown'      , $htmlSafe),
            'markdown_extra'   => new \Twig_SimpleFilter($this->richtext, 'markdown_extra', $htmlSafe),
            'smartypants'      => new \Twig_SimpleFilter($this->richtext, 'smartypants'),
            'smartypants_typo' => new \Twig_SimpleFilter($this->richtext, 'smartypants_typo'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'richtext';
    }
}
