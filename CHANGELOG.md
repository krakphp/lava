# Change Log

## Unreleased

## 0.3.3 - 2017-05-03

### Fixed

- Removed Controller validate merging of server request parameters
- Http Concern Bug #31

### Added

- Concerns\Http now uses the Http\Concerns\Response trait
- Middleware\LavaLink now extends the Http\Middleware\HttpLink
- Console\Application::mergeHelperSets #33
- LavaPackage now uses the HttpServiceProvider to define services

## 0.3.2 - 2017-04-27

### Added

- Http Controller Convenience Methods #27
- Validation Package #18
- Aborts and Exceptions #21

### Fixed

- expects and parseJson behavior now properly parse content types. #22
- Refactored Bootstrap #19 to not use event emitter to allow wrapping

## 0.3.1 - 2017-04-01

### Added

- Class Middleware #15 - You can now register classes as stack handlers.
- Console documentation
- CommandDefinitionWrapper for a fluent interface for defining commands
- Closure Commands and ``addCommand``

## 0.3.0 - 2017-03-31

### Added

- More monolog options for configuration
- Stacks Concern ability
- Tests for concerns
- error logging #7
- `handleRequest` to easily resolve requests into responses via the application
- names to all middleware so that they can be manipulated easier
- More tests for the separate packages
- More documentation

### Changed

- Semantics of Paths. Paths are now added into the application and default paths are provided to the users, but can be changed at any time.
- Added documentation on paths
- Moved app logic into separate concerns in the `Concerns` module.
- Upgraded mw dependency to 0.5.1
- Separated bootstrap from http freezing because the app can be used for more than just http.

### Fixed

- Fixed typo in composer.json suggest

## 0.2.0 - 2017-03-26

### Added

- Initial tests for packages

### Changed

- Semantics of Packages. `with` now is essentially the same as `register` except it type hints a `Lava\Application`.
- ExceptionHandler package error renderer to integrate better with the Symfony debug library.
- Slimmed down dependencies and moved them to composer suggests.

## 0.1.0 - 2017-03-19

Initial Release
