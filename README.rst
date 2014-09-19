php-richtext
============

This package provides :

* a simple wrapper allowing to use `Markdown(+Extra)`_ and `SmartyPants(+Typographer)`_, together or alone
* a service provider for the `Pimple`_ DI Container
* an extension for the `Twig`_ template engine

`Markdown(+Extra)`_ and `SmartyPants(+Typographer)`_ packages come from `Michel Fortin`_.
Thanks to him for its great work!

php-richtext works with PHP 5.3 or later.

Installation
------------

The recommended way to install php-richtext is through `Composer`_. Just create a
``composer.json`` file and run the ``php composer.phar install`` command to
install it:

.. code-block:: json

    {
        "require": {
            "freepius\php-richtext": "~1.0"
        }
    }

Usage
-----

Use from static functions:

.. code-block:: php

    <?php

    require_once __DIR__.'/../vendor/autoload.php';

    echo Freepius\Richtext::full($text, $options);

    echo Freepius\Richtext::markdown($text, $options);

    echo Freepius\Richtext::markdown_extra($text, $options);

    echo Freepius\Richtext::smartypants($text, $options);

    echo Freepius\Richtext::smartypants_typo($text, $options);


Use from an instance of the ``Richtext`` class:

.. code-block:: php

    <?php

    require_once __DIR__.'/../vendor/autoload.php';

    $richtext = new Freepius\Richtext($defaultOptions);

    echo $richtext->full($text);

    echo $richtext->markdown($text);

    echo $richtext->markdown_extra($text, $options);

    /*...*/


Use as services of a `Pimple`_ DI Container:

.. code-block:: php

    <?php

    require_once __DIR__.'/../vendor/autoload.php';

    $c = new Pimple\Container;

    $c->register(new Freepius\Pimple\Provider\RichtextProvider(), array(
        'richtext.options' => array(/*some options here*/),
    ));

    echo $c['richtext']->full($text);

    echo $c['richtext']->markdown($text);

    echo $c['richtext.full']($text);

    echo $c['richtext.markdown']($text);

    /*...*/


Since `Silex`_ uses internally the `Pimple`_ DI Container, you can use ``php-richtext`` with `Silex`_:

.. code-block:: php

    <?php

    require_once __DIR__.'/../vendor/autoload.php';

    use Freepius\Pimple\Provider\RichtextProvider;
    use Silex\Application;
    use Symfony\Component\HttpFoundation\Request;

    $app = new Application();

    $app->register(new RichtextProvider(), array(
        'richtext.options' => array(/*some options here*/),
    ));

    $app->post('/blog/render-text', function (Application $app, Request $request) {
        return $app['render.full']($request->get('text'));
    });

    $app->run();


If `Twig`_ is installed, you can also use the richtext filters in your `Twig`_ templates:

.. code-block:: php

    <?php

    require_once __DIR__.'/../vendor/autoload.php';

    /* From there, Twig is assumed to be loaded */

    $richtext = new Freepius\Richtext($options);

    $twig->addExtension(
        new Freepius\Twig\Extension\RichtextTwigExtension($richtext)
    );

    /* Or, if you use Twig through Silex,
     * to register the RichtextProvider will add automatically the twig extension!
     */

.. code-block:: twig

    {{ 'This is a <<markdown-extra>> and/or ,,smartypants-typo`` text.' | richtext }} {# full render #}

    {{ 'This is a <<markdown-extra>> and/or ,,smartypants-typo`` text.' | markdown(some_options) }}

    {{ 'This is a <<markdown-extra>> and/or ,,smartypants-typo`` text.' | markdown_extra }}

    {{ 'This is a <<markdown-extra>> and/or ,,smartypants-typo`` text.' | smartypants }}

    {{ 'This is a <<markdown-extra>> and/or ,,smartypants-typo`` text.' | smartypants_typo }}

Options
-------

TODO

Tests
-----

To run the test suite, you need `Composer`_:

.. code-block:: bash

    $ php composer.phar install --dev
    $ vendor/bin/phpunit

License
-------

php-richtext is licensed under the CC0 license.

.. _Composer:                   http://getcomposer.org
.. _Pimple:                     http://pimple.sensiolabs.org
.. _Twig:                       http://twig.sensiolabs.org
.. _Silex:                      http://silex.sensiolabs.org
.. _Michel Fortin:              https://michelf.ca
.. _Markdown(+Extra):           https://michelf.ca/projets/php-markdown
.. _SmartyPants(+Typographer):  https://michelf.ca/projets/php-smartypants
