<?php

namespace Freepius\Tests\Twig\Extension;

use Freepius\Richtext;
use Freepius\Twig\Extension\RichtextTwigExtension;

class RichtextTwigExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->twig = new \Twig_Environment(new \Twig_Loader_String());

        $this->twig->addExtension(
            new RichtextTwigExtension(new Richtext())
        );
    }

    public function testRichtextFilter()
    {
        $this->assertEquals(
            "<p><strong>Hello &#8211; World</strong></p>\n",
            $this->twig->render("{{ '**Hello -- World**' | richtext }}")
        );
    }

    public function testMarkdownFilter()
    {
        $this->assertEquals(
            "<p><strong>Hello -- World</strong></p>\n",
            $this->twig->render("{{ '**Hello -- World**' | markdown }}")
        );
    }

    public function testSmartypantsFilter()
    {
        $this->assertEquals(
            "**Hello &#8211; World**",
            $this->twig->render("{{ '**Hello -- World**' | smartypants }}")
        );
    }
}
