# Craft reCAPTCHA 3 Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 1.1.4 - 2021-08-09
### Fixed
  - Change front-end script to request from the current site's base URL instead of a relative base path. Should fix a bug with multi-site projects which are disabled. [#22]
  - Update composer

[#22]: https://github.com/clarknelson/craft-recaptcha-3/issues/22

## 1.1.3 - 2020-11-18
### Fixed
  - Update path for asset bundle files in settings template (Thanks @RobCompactCode)

## 1.1.2 - 2020-11-12
### Fixed
  - Update path for asset bundle files (Thanks @RobCompactCode)

## 1.1.1 - 2020-10-29
### Fixed
- Fix Composer 2 autoloading for assetbundles (Thanks @brandonkelly)

## 1.1.0 - 2020-09-11
### Improvement
- Add .env variables and template checks (Thanks @nickolasjadams)
- Checks for google script before loading captcha (Thanks @billythekid)
- Add link to Google's admin panel to easily get new keys
- Update readme.md to give credit to github contributors

## 1.0.15 - 2020-02-27
### Improvement
- Better handling in default controller action. Should throw 404 error if no request key included.

## 1.0.14 - 2020-02-27
### Improvement
- XML Request will listen to `ondone` instead of `onreadystatechange`. Function is called less times and more reliably.

## 1.0.13 - 2020-02-20
### Fixed
- Remove depreciation warning regarding user sessions.

## 1.0.12 - 2020-02-13
### Improvement
- Prevent controller actions from being recognized as routes.
- Update composer dependencies

## 1.0.11 - 2019-08-21
### Fixed
- Updated minimum craft requirements in order to use the `plugin()` function.

## 1.0.10 - 2019-07-31
### Improvement
- Replace the hard coded "admin" with a config option for people who have changed that in the settings.

## 1.0.9 - 2019-07-31
### Improvement
- Replace the hard coded "actions" with a config option for people who have changed that in the settings.

## 1.0.8 - 2019-07-22
### Fixed
- Remove a console log that I had forgot.

## 1.0.7 - 2019-07-22
### Improvement
- We are now passing the recaptcha response to the success and callback functions. This response will provide more information regarding the score the user recieved. See the updated docs.
- We have added a `recaptcha_callback(repsonse)` function if you would like to handle the success / failure yourself, or use a single function instead of two.

## 1.0.6 - 2019-06-18
### Fixed
- I did not release the correct way using tags, trying again.

## 1.0.5 - 2019-06-18
### Improvement
- I have removed jQuery from the front-end code, this should not be a dependency anymore even though it is common in many environments. I even used XMLHttpRequest to support Internet Explorer.
- Instead of using the CSRF token tag, which may react poorly with caching systems, we are now fetching this token directly from the server using JS.

## 1.0.4 - 2019-04-04
### Fixed
- Did not increment the version in composer so the plugin did not update in craft. Hopefully everyone will get the 1.0.4 release.

## 1.0.3 - 2019-04-03
### Fixed
- Bug with front-end template where the action was output as variable not string

### Improvement
- Added keywords to the composer.json

## 1.0.2 - 2019-04-03
### Fixed
- Bug with front-end template where the action is not being output

### Improvement
- Re-order the changelog to be chronological

## 1.0.1 - 2019-04-03
### Added
- New "action" config setting

## 1.0.0 - 2019-03-20
### Added
- Initial release
