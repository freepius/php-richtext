<?php

namespace Freepius\Twig\Extension;

use Freepius\RichtextInterface;

class RichtextTwigExtension extends \Twig_Extension
{
    protected $richtext;

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
            new \Twig_SimpleFilter('richtext'   , array($this->richtext, 'transform')  , $htmlSafe),
            new \Twig_SimpleFilter('markdown'   , array($this->richtext, 'markdown')   , $htmlSafe),
            new \Twig_SimpleFilter('smartypants', array($this->richtext, 'smartypants'), $htmlSafe),
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
