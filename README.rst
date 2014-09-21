php-richtext
============

This package provides :

* a simple wrapper allowing to use `Markdown(Extra)`_ and `SmartyPants(Typographer)`_, together or alone
* a service provider for the `Pimple`_ DI Container
* an extension for the `Twig`_ template engine

`Markdown(Extra)`_ and `SmartyPants(Typographer)`_ packages come from `Michel Fortin`_.
Thanks to him for its great work!

**php-richtext** works with *PHP 5.3* or later.


Installation
------------

The recommended way to install **php-richtext** is through `Composer`_. Just create a
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

Use from an instance of the ``Richtext`` class:

.. code-block:: php

    <?php

    require_once __DIR__.'/../vendor/autoload.php';

    $richtext = new Freepius\Richtext($config);

    echo $richtext->transform($text);

    echo $richtext->markdown($text);

    echo $richtext->smartypants($text);


Use as services of a `Pimple`_ DI Container:

.. code-block:: php

    <?php

    require_once __DIR__.'/../vendor/autoload.php';

    $c = new Pimple\Container();

    $c->register(new Freepius\Pimple\Provider\RichtextProvider(), array(
        'richtext.config' => array(/*config here*/),
    ));

    echo $c['richtext']->transform($text);

    echo $c['richtext']->markdown($text);

    echo $c['richtext']->smartypants($text);


Since `Silex`_ uses internally the `Pimple`_ DI Container, you can use **php-richtext** with `Silex`_:

.. code-block:: php

    <?php

    require_once __DIR__.'/../vendor/autoload.php';

    use Freepius\Pimple\Provider\RichtextProvider;
    use Silex\Application;
    use Symfony\Component\HttpFoundation\Request;

    $app = new Application();

    $app->register(new RichtextProvider(), array(
        'richtext.config' => array(/*config here*/),
    ));

    $app->post('/blog/render-text', function (Application $app, Request $request) {
        return $app['richtext']->transform($request->get('text'));
    });

    $app->run();


If `Twig`_ is installed, you can also use the richtext filters in your `Twig`_ templates:

.. code-block:: php

    <?php

    require_once __DIR__.'/../vendor/autoload.php';

    /* From there, Twig is assumed to be loaded */

    $richtext = new Freepius\Richtext($config);

    $twig->addExtension(
        new Freepius\Twig\Extension\RichtextTwigExtension($richtext)
    );

.. code-block:: twig

    {{ 'Here a <<markdown-extra>> and/or ,,smartypants-typo`` text.' | richtext }}

    {{ 'Here a <<markdown-extra>> and/or ,,smartypants-typo`` text.' | markdown }}

    {{ 'Here a <<markdown-extra>> and/or ,,smartypants-typo`` text.' | smartypants }}


**Note for Silex:** If you use Twig through Silex, first register the ``TwigServiceProvider``,
then register the ``RichtextProvider``. This one will add automatically the twig extension!


Configuration
-------------

The constructor of ``Richtext`` class accepts the following configuration parameters
(as an associative array):

* locale:

  * type        : ``string``
  * default     : ``null``
  * description : if defined, the `SmartyPants(Typographer)`_ will be configured
    depending on this locale. Presently, only 'en' (de facto) and 'fr' are handled.

* extra:

  * type        : ``bool``
  * default     : ``true``
  * description : if ``true``, ``MarkdownExtra`` is used (instead of ``Markdown``)

* typo:

  * type        : ``bool``
  * default     : ``true``
  * description : if ``true``, ``SmartyPantsTypographer`` is used (instead of ``SmartyPants``)

* smartypants.attr:

  * type        : ``string``
  * default     : ``SMARTYPANTS_ATTR_LONG_EM_DASH_SHORT_EN``
  * description : attributes to pass to `SmartyPants(Typographer)`_ constructor

* remove.script.tags:

  * type        : ``bool``
  * default     : ``true``
  * description : if ``true``, remove the ``<script>`` tags of the final html


**Note:** If ``locale`` is defined and ``smartypants.attr`` is ``null``,
``smartypants.attr`` is guessed according to ``locale``.
Presently, only 'en' (de facto) and 'fr' are handled.


Tests
-----

**Warning:** presently, no test is implemented yet!

To run the test suite, you need `Composer`_:

.. code-block:: bash

    $ php composer.phar install --dev
    $ vendor/bin/phpunit


License
-------

**php-richtext** is licensed under the **CC0** license.

.. _Composer:                   http://getcomposer.org
.. _Pimple:                     http://pimple.sensiolabs.org
.. _Twig:                       http://twig.sensiolabs.org
.. _Silex:                      http://silex.sensiolabs.org
.. _Michel Fortin:              https://michelf.ca
.. _Markdown(Extra):            https://michelf.ca/projets/php-markdown
.. _SmartyPants(Typographer):   https://michelf.ca/projets/php-smartypants
