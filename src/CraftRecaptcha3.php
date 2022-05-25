<?php
/**
 * Craft reCAPTCHA 3 plugin for Craft CMS 3.x
 *
 * Verifies via Google the site and secret codes required to verify humanity through reCAPTCHA v3.
 *
 * @link      http://clarknelson.com
 * @copyright Copyright (c) 2019 Clark Nelson
 */

namespace clarknelson\craftrecaptcha3;

use clarknelson\craftrecaptcha3\models\Settings;
use clarknelson\craftrecaptcha3\twigextensions\DefaultTwigExtension;
use clarknelson\craftrecaptcha3\services\DefaultService;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\web\View;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterTemplateRootsEvent;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Clark Nelson
 * @package   CraftRecaptcha3
 * @since     1.0.0
 *
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class CraftRecaptcha3 extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * CraftRecaptcha3::$plugin
     *
     * @var CraftRecaptcha3
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public string $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * CraftRecaptcha3::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->setComponents([
			'captcha' => DefaultService::class
		]);

        // Register template directory for users to call
        Event::on(
            View::class,
            View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS,
            function (RegisterTemplateRootsEvent $event) {
                $event->roots['_recaptcha'] = __DIR__ . '/templates';
            }
        );

        if ($this->request->getIsSiteRequest()) {
            // Add in our Twig extension
            $this->view->registerTwigExtension(new DefaultTwigExtension());
        }
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel(): ?\craft\base\Model
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate(
            'craft-recaptcha-3/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}