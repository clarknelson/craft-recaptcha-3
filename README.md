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

To validate users, use the `{% include ['_recaptcha/frontend'] %}` tag on the frontend. This will inject a script to communicate between your website and Google.

You can also pass the action name by passing the `action` variable in the `include` i.e. `{% include ['_recaptcha/frontend'] with {'action': 'login'} %}`. The default value for `action` is ``"contact"``. Information about Actions [can be found here](https://developers.google.com/recaptcha/docs/v3#actions).

If the recaptcha is validated, the `recaptcha_success` function is run. If the user fails the recaptcha, a `recaptcha_failure` function is run. Both must be available on the window object (i.e. `window.recaptcha_success = function(){ ... }`)

## Craft reCAPTCHA 3 Roadmap

Some things to do, and ideas for potential features:

* Custom callback for success and failure
* Bug fixes (once opened)

Brought to you by [Clark Nelson](http://clarknelson.com)
