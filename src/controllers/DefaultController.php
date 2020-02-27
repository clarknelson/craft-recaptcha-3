<?php

/**
 * Craft reCAPTCHA 3 plugin for Craft CMS 3.x
 *
 * Verifies via Google the site and secret codes required to verify humanity through reCAPTCHA v3.
 *
 * @link      http://clarknelson.com
 * @copyright Copyright (c) 2019 Clark Nelson
 */

namespace clarknelson\craftrecaptcha3\controllers;

use clarknelson\craftrecaptcha3\CraftRecaptcha3;

use Craft;
use craft\web\Controller;

/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Clark Nelson
 * @package   CraftRecaptcha3
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = true;

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/craft-recaptcha-3/default
     *
     * @return mixed
     */
    public function actionIndex()
    {
        // $this->requireCpRequest();
        $request = Craft::$app->request->post();
        $client = new \GuzzleHttp\Client();
        $secret = CraftRecaptcha3::$plugin->getSettings()->secretKey;
        if (array_key_exists('response', $request)) {
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'query' => [
                    'secret' => $secret,
                    'response' => $request['response']
                ]
            ]);
            return $response->getBody();
        } else {
            return $this->asErrorJson('There was no response key attached to the request, we can not continue without this key.');
        }
    }

    public function actionCheckSettings()
    {
        $settings = CraftRecaptcha3::$plugin->getSettings();

        if (!$settings->siteKey) {
            throw new \Exception('The Site Key is empty for the Craft Recaptcha 3 Plugin. Please visit the settings page for the plugin to provide this information.');
        }

        if (!$settings->secretKey) {
            throw new \Exception('The Secret Key is empty for the Craft Recaptcha 3 Plugin. Please visit the settings page for the plugin to provide this information.');
        }

        return true;
    }

    public function beforeAction($action)
    {
        $this->actionCheckSettings();

        if (!parent::beforeAction($action)) {
            return false;
        }

        return true;
    }
}
