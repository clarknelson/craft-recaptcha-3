<?php
/**
 * Craft reCAPTCHA 3 plugin for Craft CMS 3.x
 *
 * Verifies via Google the site and secret codes required to verify humanity through reCAPTCHA v3.
 *
 * @link      http://clarknelson.com
 * @copyright Copyright (c) 2019 Clark Nelson
 */

namespace clarknelson\craftrecaptcha3\models;

use clarknelson\craftrecaptcha3\CraftRecaptcha3;

use Craft;
use craft\base\Model;

/**
 * CraftRecaptcha3 Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Clark Nelson
 * @package   CraftRecaptcha3
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
    public $siteKey = '';
    public $secretKey = '';

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['siteKey', 'string'],
            ['secretKey', 'string'],
            [['siteKey', 'secretKey'], 'required']
        ];
    }
}
