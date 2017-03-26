# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

### Added

- Initial tests for packages

### Changed

- Semantics of Packages. `with` now is essentially the same as `register` except it type hints a `Lava\Application`.
- ExceptionHandler package error renderer to integrate better with the Symfony debug library.

### [0.1.0] - 2017-03-19

Initial Release