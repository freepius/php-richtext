<?php

namespace Freepius;

interface RichTextInterface
{
    public function full($text);

    public function markdown($text);

    public function markdown_extra($text);

    public function smartypants($text);

    public function smartypants_typo($text);
}
