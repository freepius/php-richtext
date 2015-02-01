<?php

namespace Freepius\Tests;

use Freepius\Richtext;

class RichtextTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigMerging()
    {
        $rt = new Richtext();
        $this->assertEquals($rt->getConfig(), Richtext::$DEFAULT_CONFIG);

        $rt = new Richtext(array('locale' => 'Fake'));
        $config = $rt->getConfig();
        $this->assertEquals('Fake', $config['locale']);
    }

    public function testLocaleSupport()
    {
        $rt = new Richtext();

        // *locale* is defined but *smartypants.attr* is not null.
        // So *smartypants.attr* is not guessed.
        $rt = new Richtext(array('locale' => 'fr', 'smartypants.attr' => 0));
        $config = $rt->getConfig();
        $this->assertEquals(0, $config['smartypants.attr']);

        // Because 'fr' is a handled locale, *smartypants.attr* is guessed
        $rt = new Richtext(array('locale' => 'fr', 'smartypants.attr' => null));
        $config = $rt->getConfig();
        $this->assertEquals(Richtext::$SMARTYPANTS_ATTR_BY_LOCALE['fr'], $config['smartypants.attr']);

        // Because 'Fake' is not a handled locale, *smartypants.attr* stays to null
        $rt = new Richtext(array('locale' => 'Fake', 'smartypants.attr' => null));
        $config = $rt->getConfig();
        $this->assertEquals(null, $config['smartypants.attr']);
    }


    /********** Markdown tests **********/


    public function testMarkdownBasicOnly()
    {
        $rt = new Richtext(array('extra' => false));

        $this->assertEquals(
            "<p>A valid <strong>markdown</strong> text.</p>\n",
            $rt->markdown("A valid **markdown** text.")
        );

        $this->assertEquals(
            "<div markdown='1'>This is *true* markdown extra test.</div>\n",
            $rt->markdown("<div markdown='1'>This is *true* markdown extra test.</div>")
        );
    }

    public function testMarkdownExtra()
    {
        $rt = new Richtext(array('extra' => true));

        $this->assertEquals(
            "<div>\n\n<p>This is <em>true</em> markdown extra test.</p>\n\n</div>\n",
            $rt->markdown("<div markdown='1'>This is *true* markdown extra test.</div>")
        );
    }

    public function testMarkdownProduceHtml5()
    {
        $rt = new Richtext();

        $this->assertEquals(
            "<p>Hello<br>\nWorld</p>\n",
            $rt->markdown("Hello  \nWorld")
        );

        $this->assertEquals(
            "<p><img src=\"/my/image.jpg\" alt=\"My image\"></p>\n",
            $rt->markdown("![My image](/my/image.jpg)")
        );
    }

    public function testMarkdownExtraAvoidFootnoteConflict()
    {
        $rt = new Richtext();
        $text = "Text[^1]\n\n[^1]: My footnote";
        $this->assertNotEquals($rt->markdown($text), $rt->markdown($text));
    }

    public function testMarkdownRemoveScriptTags()
    {
        $rt = new Richtext(array('remove.script.tags' => true));

        $this->assertEquals(
            "<p>HelloWorld</p>\n",
            $rt->markdown("Hello<script>alert('Boom');</script>World")
        );

        $this->assertEquals(
            "<p>Hello</p>\n",
            $rt->markdown("Hello< script id='foo' class='bar' >alert('Boom'); World")
        );

        $this->assertEmpty(trim(
            $rt->markdown("<script>One</script><script>Two</script>")
        ));
    }

    public function testMarkdownKeepScriptTags()
    {
        $rt = new Richtext(array('remove.script.tags' => false));

        $this->assertEquals(
            "<p>Hello<script>alert('Boom');</script>World</p>\n",
            $rt->markdown("Hello<script>alert('Boom');</script>World")
        );
    }


    /********** SmartyPants tests **********/


    public function testSmartyPantsBasicOnly()
    {
        $rt = new Richtext(array('typo' => false, 'locale' => 'fr', 'smartypants.attr' => null));

        $this->assertEquals(
            "Hello&#8211;World! What&#8217;s your weight? &#8212;70kg!",
            $rt->smartypants("Hello--World! What's your weight? ---70kg!")
        );
    }

    public function testSmartyPantsTypographer()
    {
        $rt = new Richtext(array('typo' => true, 'locale' => 'fr', 'smartypants.attr' => null));

        $this->assertEquals(
            "Hello &#8211; World&#160;! What&#8217;s your weight&#160;? &#8212; 70&#160;kg&#160;!",
            $rt->smartypants("Hello--World! What's your weight? ---70kg!")
        );
    }

    public function testSmartyPantsTypographerFrenchDoubleQuote()
    {
        $rt = new Richtext(array('locale' => 'fr'));
        $this->assertEquals('&#171;Bonjour&#187;', $rt->smartypants('"Bonjour"'));
    }
}
