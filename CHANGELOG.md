# Craft reCAPTCHA 3 Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

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
