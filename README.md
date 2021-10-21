# Craft reCAPTCHA 3 plugin for Craft CMS 3.x

A Craft CMS 3 plugin to verify the user's humanity via Google's reCAPTCHA v3.

## Craft reCaptcha 3 Overview

Google's reCaptcha service is the industry leader in determining whether a website visitor is human or robot. Their newest version (v3) does not require any human challenge such as a checkbox. Google will determine whether the user is human based on their browser characteristics, visiting history, and cookie information. The request to Google must come from a server, not a browser, which is where this plugin comes in. It attempts to take the busy work out of validating reCaptcha with Google by providing a drop-in solution.

Because of the low user friction, this may not be the most secure or reliable service in filtering bots. It will simply return whether or not Google thinks the current user is a bot. You may also need a checkbox captcha if the score does not pass and the user is likely a bot. There is a very good [hCaptcha Plugin](https://plugins.craftcms.com/craft-hcaptcha) which i've found to have the best success in preventing bots. Many contact form plugins also have options for including captcha security. See FAQ page for more information: [https://developers.google.com/recaptcha/docs/faq#should-i-use-recaptcha-v2-or-v3](https://developers.google.com/recaptcha/docs/faq#should-i-use-recaptcha-v2-or-v3)

I hope this plugin helps in your spam prevention journey!

## Requirements

This plugin requires Craft CMS 3.0.0 or later. Google reCAPTCHA account (with v3 site / secret keys). 

## Installation

To install the plugin, follow these instructions.

```bash
composer require clarknelson/craft-recaptcha-3
craft plugin/install craft-recaptcha-3
```

## Configuring Craft reCAPTCHA 3

To configure the plugin, simply provide the site key and secret key in the settings screen. Please make sure that these keys are to the v3 version of the plugin, or else Google's server will return a 400 error.

Settings may be optionally configured using a config file.

Create `config/craft-recaptcha-3.php`

```php
<?php
return [
    'siteKey' => getenv("RECAPTCHA_SITEKEY"),
    'secretKey' => getenv("RECAPTCHA_SECRETKEY"),
];
```

and then move your keys in your env.

## Using Craft reCAPTCHA 3

The 1.2.0 update brings a more friendly API to our plugin. 

There are two new ways to include the recaptcha on the page.

```twig
{# This is a "simple" version that will instantly request a score when ready #}
{{ craftRecaptcha3({ 
    action: 'foobar', 
    success: 'recaptchaSuccess', 
    failure: 'recaptchaFailure' 
}) | raw }}
```

```twig
{# This is a "form" version that will prevent the form from submitting until validated #}
<form method="post" accept-charset="UTF-8">
    {{ csrfInput() }}
    {{ actionInput('users/save-user') }}
    {{ craftRecaptcha3Form({ 
            action: 'contactForm', 
            success: 'recaptchaSuccess', 
            failure: 'recaptchaFailure' 
    }) | raw }}
    ...
</form>
```

You can define what the success / failure javascript callbacks will be called.

The "action" parameter for tracking within reCaptcha is also available.


```js
// these functions can also be included directly in the template with {% js %}{% endjs %} tags
// just an example to get you started!
let recaptchaSuccess = function (response, event) {
    console.log('Successful registration', response);
    if(event){ // null if simple version is used
        event.target.submit(); // submit the form on success!
    }
};
          
let recaptchaFailure = function (response, event) {
    // console.log(response);
    console.error('We could not verify the user with Google reCaptcha 3: '+response['error-codes'].join(','))
};
```

## Using Craft reCAPTCHA 3 (Pre 1.2.0)

I have kept the legacy script until the 2.0.0 release. Please attempt to update your API if possible.

The invisible reCaptcha has two parts, a front-end request and a back-end request. This code will inject a script to communicate between your website and Google. (Can be anywhere on page, basically the simple version with explicit callback functions)

```php
{% include ['_recaptcha/frontend'] %}
```

You can also include an action name by passing the `action` variable to the script. More information about actions [can be found here](https://developers.google.com/recaptcha/docs/v3#actions).

```php
{% include ['_recaptcha/frontend'] with {'action': 'contact'} %}
```

The following javascript functions will be called once the response from Google is received:

```js

// Called if there is a successful response
window.recaptcha_callback = function(response){
    console.log(response);
}
// Called only if the user passes the challenge
window.recaptcha_success = function(response){
    console.log(response);
}
// Called only if the user fails the challenge
window.recaptcha_failure = function(response){
    console.log(response);
}
```

Please make one or all of these functions available in the Javascript runtime to be called.

## Craft reCAPTCHA 3 Road Map

Some things to do, and ideas for potential features:

* Make response / functions available to backend plugins (please see my "DefaultService" to see if it may fit your needs)
* Add option to hide recaptcha badge

Brought to you by [Clark Nelson](https://clarknelson.com) and GitHub contributors like you!
