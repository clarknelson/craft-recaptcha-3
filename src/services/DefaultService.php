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


    public $settings;
    public $cpTrigger;
    public $actionTrigger;
    public $csrfTokenName;
    public $csrfTokenValue;
    public $siteKey;

    /**
     * Initializes the service, stores some commonly used variables.
     */
    public function init()
    {
        $this->settings = null;
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
     *     CraftRecaptcha3::$plugin->captcha->loadRecaptchaScript()
     *
     * @return mixed
     */
    public function loadRecaptchaScript()
    {
        $settings = CraftRecaptcha3::$plugin->getSettings();
        if (!$settings->siteKey) {
            return <<<EOD
            <script>
                console.error("The site key was not set for the Craft Recaptcha 3 plugin.The site key was not set for the Craft Recaptcha 3 plugin. Please visit the settings page to add this key.");
            </script>
            EOD;
        } else {
            return <<<EOD
            <script src="https://www.google.com/recaptcha/api.js?render=$settings->siteKey"></script>
            EOD;
        }
        // Craft::$app->getView()->registerAssetBundle(CraftRecaptcha3Asset::class);
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
        $this->settings = $settings;
        $requestScoreScript = $this->_requestScoreScript('requestScore');
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
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     CraftRecaptcha3::$plugin->captcha->recaptchaExectue()
     *
     * @return mixed
     */
    public function recaptchaForm($settings)
    {
        $this->settings = $settings;
        $requestScoreScript = $this->_requestScoreScript('requestScore');
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

    public function _requestScoreScript($funcName)
    {
        $action = $this->settings['action'] ?? 'contact';
        $successCallback = $this->settings['success'] ?? 'recaptcha_success';
        $failureCallback = $this->settings['failure'] ?? 'recaptcha_failure';

        return <<<EOD
        var $funcName = function (event) {
            // this will help signal when the ajax has completed
            let success = undefined; 
            let response = undefined;
            let f = typeof form !== 'undefined' ? form : null;

            if (typeof event !== 'undefined') {
                event.preventDefault();
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

            let listen = setInterval(() => {
                if(success == true || success == false){
                    clearInterval(listen);
                    if(success){
                        if (typeof $successCallback !== 'undefined') {
                            $successCallback(response, f);
                        }
                    } else {
                        if (typeof $failureCallback !== 'undefined') {
                            $failureCallback(response, f);
                        }
                    }
                } else {
                    // do nothing really, wait for ajax to finish
                    // console.log(success);
                }
            }, 100);
        };
        EOD;
    }
}
