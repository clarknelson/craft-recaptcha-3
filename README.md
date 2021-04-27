# Craft reCAPTCHA 3 plugin for Craft CMS 3.x

A Craft CMS 3 plugin to verify the user's humanity via Google's reCAPTCHA v3.

## Requirements

This plugin requires Craft CMS 3.0.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require clarknelson/craft-recaptcha-3

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Craft reCAPTCHA 3.

## Craft reCAPTCHA 3 Overview

Google's Recaptcha service is the industry leader in determining whether a website visitor is human or robot. Their newest version (v3) does not even require any human input. It will determine whether the user is human based on their visitor characteristics. Unfortunately, the request to Google must come from a server, not a browser, which is where this plugin comes in. It attempts to take the busy work out of validating recaptcha with Google by providing a drop-in solution.

## Configuring Craft reCAPTCHA 3

To configure the plugin, simply provide the site key and secret key in the settings screen. These two values are provided when you create a new site in the admin panel: <a href="https://www.google.com/recaptcha/intro/v3.html">https://www.google.com/recaptcha/intro/v3.html</a>.

## Using Craft reCAPTCHA 3

The invisible recaptcha has two parts, a front-end request and a back-end request. This code will inject a script to communicate between your website and Google. (Can be anywhere on page)

```php
{% include ['_recaptcha/frontend'] %}
```

You can also include an action name by passing the `action` variable to the script. More information about actions [can be found here](https://developers.google.com/recaptcha/docs/v3#actions).

```php
{% include ['_recaptcha/frontend'] with {'action': 'contact'} %}
```

The following javascript functions will be called once the responce from Google is recieved:

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

Please make one or all of these functions available in the Javascript runtime to be called. It's worth noting that 

## Configuration

Settings may be optionally configured using a config file.

Create `config/craft-recaptcha-3.php`

```php
<?php
return [
	"siteKey" => getenv("GOOGLE_SITEKEY"),
	"secretKey" => getenv("GOOGLE_SECRETKEY")
];
```
and then move your keys in your env.

## Craft reCAPTCHA 3 Roadmap

Some things to do, and ideas for potential features:

* Make response available to backend plugins?

Brought to you by [Clark Nelson](https://clarknelson.com) and lovely GitHub contributors like you!
