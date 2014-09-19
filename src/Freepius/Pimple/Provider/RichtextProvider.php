<?php

namespace Freepius\Pimple\Provider;

use Freepius\Richtext;
use Freepius\Twig\Extension\RichtextTwigExtension;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Integration of Markdown-Extra and SmartyPants-Typographer
 * for Pimple container.
 */
class RichtextProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $c)
    {
        // Options

        $c['richtext.options'] = array();

        if (isset($c['locale']) && $c['locale']) {
            $c['richtext.options']['locale'] = $c['locale'];
        }

        // Services

        $c['richtext'] = function ($c) {
            return new Richtext($c['richtext.options']);
        };

        $transformerTypes = [
            'full',
            'markdown', 'markdown_extra',
            'smartypants', 'smartypants_typo'
        ];

        foreach ($transformerTypes as $type)
        {
            $c["richtext.$type"] = $c->protect(
                function ($text, array $options = []) use ($c) {
                    return $c['richtext']->{$type}($text, $options);
                }
            );
        }

        // Twig extension

        if (isset($c['twig']) && $c['twig'] instanceof \Twig_Environment) {
            $c['twig'] = $c->extend('twig', function($twig, $c) {
                $twig->addExtension(new RichtextTwigExtension($c['richtext']));
                return $twig;
            });
        }
    }
}
