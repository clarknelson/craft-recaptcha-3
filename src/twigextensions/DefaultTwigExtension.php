<?php

/**
 * Test Plugin plugin for Craft CMS 3.x
 *
 * A plugin with EVERYTHING
 *
 * @link      https://clarknelson.com
 * @copyright Copyright (c) 2021 Clark Nelson
 */

namespace clarknelson\craftrecaptcha3\twigextensions;

use clarknelson\craftrecaptcha3\CraftRecaptcha3;
use clarknelson\craftrecaptcha3\assetbundles\CraftRecaptcha3\CraftRecaptcha3Asset;
use Craft;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig can be extended in many ways; you can add extra tags, filters, tests, operators,
 * global variables, and functions. You can even extend the parser itself with
 * node visitors.
 *
 * http://twig.sensiolabs.org/doc/advanced.html
 *
 * @author    Clark Nelson
 * @package   TestPlugin
 * @since     1.0.0
 */
class DefaultTwigExtension extends AbstractExtension
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'Craft Recaptcha 3 Twig Extensions';
    }

    /**
     * Returns an array of Twig filters, used in Twig templates via:
     *
     *      {{ 'something' | someFilter }}
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            // We probably do not need these yet
            // new TwigFilter('someFilter', [$this, 'someInternalFunction']),
        ];
    }

    /**
     * Returns an array of Twig functions, used in Twig templates via:
     *
     *      {% set this = someFunction('something') %}
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('craftRecaptcha2', [$this, 'craftRecaptcha2']),
            new TwigFunction('craftRecaptcha3', [$this, 'craftRecaptcha3']),
            new TwigFunction('craftRecaptcha3Form', [$this, 'craftRecaptcha3Form']),
        ];
    }

   

    /**
     * Simple Function: Will output the script to start loading recaptcha,
     * in addition to the instant request once the page loads to the server
     * doesn't require any input from the user, will auto-authenticate
     *
     * @param null $text
     *
     * @return string
     */
    public function craftRecaptcha2($settings)
    {
        $service = CraftRecaptcha3::getInstance()->captcha;

        // grecaptcha.ready(() => {
        //     grecaptcha.render('html_element', {
        //        'sitekey' : 'v2_site_key'
        //     });
        //   });
        $template = $service->loadCaptcha3Script($settings) .
            $service->recaptchaExectue($settings);
        return $template;
    }


    /**
     * Simple Function: Will output the script to start loading recaptcha,
     * in addition to the instant request once the page loads to the server
     * doesn't require any input from the user, will auto-authenticate
     *
     * @param null $text
     *
     * @return string
     */
    public function craftRecaptcha3($settings)
    {
        $service = CraftRecaptcha3::getInstance()->captcha;
        $template = $service->loadCaptcha3Script($settings) .
            $service->recaptchaExectue($settings);
        return $template;
    }


    /**
     * Form Function: Should submit authorization to the server when the parent form is submitted
     *
     * @param null $text
     *
     * @return string
     */
    public function craftRecaptcha3Form($settings)
    {
        $service = CraftRecaptcha3::getInstance()->captcha;
        $template = $service->loadCaptcha3Script($settings) .
            $service->recaptchaForm($settings);
        return $template;
    }
}
