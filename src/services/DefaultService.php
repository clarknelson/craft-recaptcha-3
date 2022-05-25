<?php

/**
 * Test Plugin plugin for Craft CMS 3.x
 *
 * A plugin with EVERYTHING
 *
 * @link      https://clarknelson.com
 * @copyright Copyright (c) 2021 Clark Nelson
 */

namespace clarknelson\craftrecaptcha3\services;

use clarknelson\craftrecaptcha3\CraftRecaptcha3;
use clarknelson\craftrecaptcha3\assetbundles\CraftRecaptcha3\CraftRecaptcha3Asset;

use Craft;
use craft\base\Component;
use craft\helpers\StringHelper;

/**
 * TestPluginService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Clark Nelson
 * @package   TestPlugin
 * @since     1.0.0
 */
class DefaultService extends Component
{
    // Public Methods
    // =========================================================================


    public $pluginSettings;
    public $cpTrigger;
    public $actionTrigger;
    public $csrfTokenName;
    public $csrfTokenValue;
    public $siteKey;

    /**
     * Initializes the service, stores some commonly used variables.
     */
    public function init(): void
    {
        $this->pluginSettings = null;
        $this->cpTrigger = Craft::$app->config->general->cpTrigger;
        $this->actionTrigger = Craft::$app->config->general->actionTrigger;
        $this->csrfTokenName = Craft::$app->config->general->csrfTokenName;
        $this->csrfTokenValue = Craft::$app->request->getCsrfToken();
        $this->siteKey = CraftRecaptcha3::$plugin->getSettings()->siteKey;

        parent::init();
    }

    /**
     * Very simple function to load the script from google's servers
     * Will check first if there is a siteKey set, which is requied to get the script
     *
     * From any other plugin file, call it like this:
     *
     *     CraftRecaptcha3::$plugin->captcha->loadCaptcha3Script()
     *
     * @return mixed
     */
    public function loadCaptcha3Script($settings)
    {
        $this->pluginSettings = CraftRecaptcha3::$plugin->getSettings();
        $siteKey = $this->pluginSettings['siteKey'];
        if (!$siteKey) {
            return <<<EOD
            <script>
                console.error("The site key was not set for the Craft Recaptcha 3 plugin.The site key was not set for the Craft Recaptcha 3 plugin. Please visit the settings page to add this key.");
            </script>
            EOD;
        } else {
            $visible = isset($settings['badge']) ? (boolean)$settings['badge'] : true;
            $hiddenStyles = $visible ? "" : "<style>.grecaptcha-badge { visibility: hidden; }</style>";

            // @TODO: Maybe we should register a js file with craft to load instead
            // Craft::$app->getView()->registerAssetBundle(CraftRecaptcha3Asset::class);
            // something like this but we probably don't need a whole asset bundle

            // @TODO: I believe this parameter will call render right away with the site key
            // it would be better if it just loaded the script and called render elsewhere

            return <<<EOD
            $hiddenStyles
            <script src="https://www.google.com/recaptcha/api.js?render=$siteKey"></script>
            EOD;
        }
    }

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     CraftRecaptcha3::$plugin->captcha->recaptchaExectue()
     *
     * @return mixed
     */
    public function recaptchaExectue($settings)
    {
        $requestScoreScript = $this->_requestScoreScript('requestScore', $settings['action'], $settings['success'], $settings['failure']);
        return <<<EOD
        <script>
        if (typeof window.grecaptcha !== "undefined") {
            window.grecaptcha.ready(function () {
                $requestScoreScript
                requestScore();
            });
        }
        </script> 
        EOD;
    }

    /**
     * This is a helper function for the twig extention that will live in a <form> element
     * A hidden input is added to the form in order to identify it.
     * If the user didn't mess up and put it the wrong place (no <form>) then it will call the request function
     *
     * From any other plugin file, call it like this:
     *
     *     CraftRecaptcha3::$plugin->captcha->recaptchaForm($settings)
     */
    public function recaptchaForm($settings)
    {
        $requestScoreScript = $this->_requestScoreScript('requestScore', $settings['action'], $settings['success'], $settings['failure']);
        $slug = StringHelper::randomString();
        return <<<EOD
        <input type="hidden" name="craft-recaptcha-3-$slug" value="true">
        <script>
        if (typeof window.grecaptcha !== "undefined") {
            window.grecaptcha.ready(function () {
                $requestScoreScript;
                let form = document.getElementsByName("craft-recaptcha-3-$slug")[0].form;
                if(form){
                    form.onsubmit = requestScore;
                } else {
                    console.error('The Craft Recaptcha 3 form code can only be used within a form element');
                }
            });
        }
        </script> 
        EOD;
    }

    /**
     * This function will define but not call a function to retrieve the score
     * It is mostly javascript, but we will need to inject a few variables from the user
     *
     * From any other plugin file, call it like this:
     *
     *     CraftRecaptcha3::$plugin->captcha->_requestScoreScript($funcName)
     */
    public function _requestScoreScript($funcName, $actionName, $successFunc, $failureFunc)
    {
        $funcName = $funcName ?? 'requestScore';
        $action = $actionName ?? 'contact';
        $successCallback = $successFunc ?? 'window.recaptcha_success';
        $failureCallback = $failureFunc ?? 'window.recaptcha_failure';

        return <<<EOD
        var $funcName = function (event) {
            let success = undefined; 
            let response = undefined;
            let f = typeof form !== 'undefined' ? form : null;

            // if this function was a callback for a JS event
            // then we want to stop the event until we verify the user
            if (typeof event !== 'undefined') {
                event.preventDefault();
            } else {
                let event = null;
            }
         
            window.grecaptcha.execute("$this->siteKey", { action: "$action" }).then(function (token) {
                var scoreRequest = new XMLHttpRequest();
                scoreRequest.onload = function () {
                    if (scoreRequest.status == 200) {
                        response = JSON.parse(scoreRequest.responseText);
                        if (response.success) {
                            success = true;
                        } else {
                            success = false;
                        }
                    } else {
                        console.error('There was a problem requesting the captcha score from the server', scoreRequest);
                    }
                };
                scoreRequest.open("POST", "/$this->actionTrigger/craft-recaptcha-3/default", true);
                scoreRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                scoreRequest.send('response=' + token + '&$this->csrfTokenName=$this->csrfTokenValue');
            });

            // this will always be running when the requestScore function is called
            // when the AJAX request to Google / our server finalizes, we will exit
            let listen = setInterval(() => {
                if(success == true || success == false){
                    clearInterval(listen);
                    if(success){
                        if (typeof $successCallback !== 'undefined') {
                            $successCallback(response, event);
                        }
                    } else {
                        if (typeof $failureCallback !== 'undefined') {
                            $failureCallback(response, event);
                        }
                    }
                } else {
                    // do nothing really... 
                    // console.log(success);
                }
            }, 100);
        };
        EOD;
    }
}
