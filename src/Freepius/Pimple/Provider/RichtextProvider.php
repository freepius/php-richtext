<?php

namespace Freepius\Pimple\Provider;

use Freepius\Richtext;
use Freepius\Twig\Extension\RichtextTwigExtension;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Integration of Markdown(Extra) and SmartyPants(Typographer)
 * for Pimple container. If twig is active then add the Twig extension.
 */
class RichtextProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $c)
    {
        // Configuration

        $c['richtext.config'] = array();

        if (isset($c['locale']) && $c['locale']) {
            $c['richtext.config'] += array('locale' => $c['locale']);

            if (in_array($c['locale'], Richtext::HANDLED_LOCALES)) {
                $c['richtext.config'] += array('smartypants.attr' => null);
            }
        }

        // Service

        $c['richtext'] = function ($c) {
            return new Richtext($c['richtext.config']);
        };

        // Twig extension

        if (isset($c['twig'])) {
            $c['twig'] = $c->extend('twig', function($twig, $c) {
                $twig->addExtension(new RichtextTwigExtension($c['richtext']));
                return $twig;
            });
        }
    }
}
