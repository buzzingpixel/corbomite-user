# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.1.0] - 2019-04-03
## Added
- Defined interfaces in DI config
### Changed
- Internal change: updated code to be compliant with Doctrine coding standards
- Removed reliance on APP_BASE_PATH constant
- Removed usage of deprecated Corbomite DI methods
- Removed deprecated Twig uses
- Updated usages of DateTime to DateTimeInterface and/or DateTimeImmutable
- Required buzzingpixel/cookie-api 2.x

## [3.0.1] - 2019-04-03
### Changed
- Allow usage of zendframework/zend-diactoros 2.x

## [3.0.0] - 2019-02-25
### Changed
- Require Corbomite Events 2.x and update to work with Corbomite Events 2.x

## [2.2.1] - 2019-01-22
### Fixed
- Fixed an issue where db skeleton was not updated for new schema

## [2.2.0] - 2019-01-22
### Changed
- Tables now use binary UUID columns rather than an auto-incrementing integer as primary key

## [2.1.2] - 2019-01-22
### Changed
- Updated tables to not use an auto-incrementing integer as primary key

## [2.1.1] - 2019-01-21
### Fixed
- Resolved a namespace issue

## [2.1.0] - 2019-01-21
### Added
- Added Api method for deleting users
- Added event dispatching when certain Api events happen ([Uses Corbomite Events](https://github.com/buzzingpixel/corbomite-events))

## [2.0.0] - 2019-01-21
### Added
- Added ability to have extended table columns by implementing application the Corbomite User package is being used in

## [1.1.3] - 2019-01-14
### Fixed
- Fixed bugs related to user data

## [1.1.2] - 2019-01-14
### Fixed
- Resolved a namespace issue

## [1.1.1] - 2019-01-14
### Fixed
- Resolved a dependency issue

## [1.1.0] - 2019-01-14
### Added
- Added an http log in action
- Added a Twig extension that exposes the UserApi to Twig
### New
- Various services and classes now implement interfaces

## [1.0.0] - 2019-01-11
### New
- Initial Release
