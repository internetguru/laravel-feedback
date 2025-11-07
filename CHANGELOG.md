# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [4.0.0] - 2025-11-07

_Stable release based on [4.0.0-rc.1]._

## [4.0.0-rc.1] - 2025-11-07

### Changed

- Implement new version of feedback, see README.md for details.

## [3.1.3] - 2025-11-06

### Fixed

- Add missing `send from` section.

## [3.1.2] - 2025-11-06

### Fixed

- Show reply-to name iff defined.

## [3.1.1] - 2025-11-06

### Fixed

- Fix header spacing.

## [3.1.0] - 2025-10-14

_Stable release based on [3.1.0-rc.1]._

## [3.1.0-rc.1] - 2025-10-14

### Added

- Configurable `linkHtml` and `linkClass`.

## [3.0.0] - 2025-10-01

_Stable release based on [3.0.0-rc.1]._

## [3.0.0-rc.1] - 2025-10-01

### Changed

- Refactor feedback to configurable livewire component. E.g.
  ```
    <livewire:ig-feedback />
    <livewire:ig-feedback
        title="Feedback"
        subject="New feedback received"
        submit="Send feedback"
        name="optional"
        email="required"
        phone="hidden"
    />
    ```

## [2.1.3] - 2025-09-24

### Fixed

- Update email templates.

## [2.1.2] - 2025-09-24

### Fixed

- Tune up email templates.

## [2.1.1] - 2025-09-24

### Changed

- Update email templates.

## [2.1.0] - 2025-08-25

_Stable release based on [2.1.0-rc.1]._

## [2.1.0-rc.1] - 2025-08-25

### Added

- Add danish translation.

## [2.0.4] - 2025-08-18

### Fixed

- Publish translation files.

## [2.0.3] - 2025-05-20

### Fixed

- Increase textarea height.

## [2.0.2] - 2025-05-15

### Fixed

- Add missing replyto.

## [2.0.1] - 2025-05-15

### Fixed

- Refactor feedback to use Notification instead of Mailable"

## [2.0.0] - 2025-05-15

_Stable release based on [2.0.0-rc.1]._

## [2.0.0-rc.1] - 2025-05-15

### Added

- Use `IgMailable` for sending feedback emails.

### Changed

- Update laravel-common version to `^2`.
- Make email field optional and use it for replyto.

### Removed

- Remove subject field.

## [1.0.0] - 2025-05-09

_Stable release based on [1.0.0-rc.1]._

## [1.0.0-rc.1] - 2025-05-09

### Added

- Use `internetguru/laravel-common` recaptcha.

### Changed

- Change laravel-common version to `^1`.

## [0.4.1] - 2025-04-25

### Fixed

- Change laravel-common version to `^0`.

## [0.4.0] - 2025-04-17

_Stable release based on [0.4.0-rc.1]._

## [0.4.0-rc.1] - 2025-04-17

### Changed

- Update laravel-common version to `^0.13`.

## [0.3.0] - 2025-04-16

_Stable release based on [0.3.0-rc.1]._

## [0.3.0-rc.1] - 2025-04-16

### Changed

- Update laravel-common version to `^0.12`.

## [0.2.0] - 2025-04-16

_Stable release based on [0.2.0-rc.1]._

## [0.2.0-rc.1] - 2025-04-16

### Changed

- Update laravel-common version to `^0.11`.

## [0.1.5] - 2025-04-11

### Fixed

- Update laravel-common version to `^0.10`.

## [0.1.4] - 2025-04-10

### Fixed

- Fix success message translation key.

## [0.1.3] - 2025-04-10

### Fixed

- Fix email success message.
- Fix route registration into web.

## [0.1.2] - 2025-04-10

### Fixed

- Disable form recaptcha.

## [0.1.1] - 2025-04-10

### Removed

- Remove unused feedback js.

## [0.1.0] - 2025-04-10

_Stable release based on [0.1.0-rc.1]._

## [0.1.0-rc.1] - 2025-04-10

## [0.0.0] - 2025-04-10

### Added

- New changelog file.

[Unreleased]: https://https://github.com/internetguru/laravel-feedback/compare/staging...dev
[4.0.0]: https://https://github.com/internetguru/laravel-feedback/compare/v3.1.3...v4.0.0
[4.0.0-rc.1]: https://github.com/internetguru/laravel-feedback/releases/tag/v3.1.3
[3.1.3]: https://https://github.com/internetguru/laravel-feedback/compare/v3.1.2...v3.1.3
[3.1.2]: https://https://github.com/internetguru/laravel-feedback/compare/v3.1.1...v3.1.2
[3.1.1]: https://https://github.com/internetguru/laravel-feedback/compare/v3.1.0...v3.1.1
[3.1.0]: https://https://github.com/internetguru/laravel-feedback/compare/v3.0.0...v3.1.0
[3.1.0-rc.1]: https://github.com/internetguru/laravel-feedback/releases/tag/v3.0.0
[3.0.0]: https://https://github.com/internetguru/laravel-feedback/compare/v2.1.3...v3.0.0
[3.0.0-rc.1]: https://github.com/internetguru/laravel-feedback/releases/tag/v2.1.3
[2.1.3]: https://https://github.com/internetguru/laravel-feedback/compare/v2.1.2...v2.1.3
[2.1.2]: https://https://github.com/internetguru/laravel-feedback/compare/v2.1.1...v2.1.2
[2.1.1]: https://https://github.com/internetguru/laravel-feedback/compare/v2.1.0...v2.1.1
[2.1.0]: https://https://github.com/internetguru/laravel-feedback/compare/v2.0.4...v2.1.0
[2.1.0-rc.1]: https://github.com/internetguru/laravel-feedback/releases/tag/v2.0.4
[2.0.4]: https://https://github.com/internetguru/laravel-feedback/compare/v2.0.3...v2.0.4
[2.0.3]: https://https://github.com/internetguru/laravel-feedback/compare/v2.0.2...v2.0.3
[2.0.2]: https://https://github.com/internetguru/laravel-feedback/compare/v2.0.1...v2.0.2
[2.0.1]: https://https://github.com/internetguru/laravel-feedback/compare/v2.0.0...v2.0.1
[2.0.0]: https://https://github.com/internetguru/laravel-feedback/compare/v1.0.0...v2.0.0
[2.0.0-rc.1]: https://github.com/internetguru/laravel-feedback/releases/tag/v1.0.0
[1.0.0]: https://https://github.com/internetguru/laravel-feedback/compare/v0.4.1...v1.0.0
[1.0.0-rc.1]: https://github.com/internetguru/laravel-feedback/releases/tag/v0.4.1
[0.4.1]: https://https://github.com/internetguru/laravel-feedback/compare/v0.4.0...v0.4.1
[0.4.0]: https://https://github.com/internetguru/laravel-feedback/compare/v0.3.0...v0.4.0
[0.4.0-rc.1]: https://github.com/internetguru/laravel-feedback/releases/tag/v0.3.0
[0.3.0]: https://https://github.com/internetguru/laravel-feedback/compare/v0.2.0...v0.3.0
[0.3.0-rc.1]: https://github.com/internetguru/laravel-feedback/releases/tag/v0.2.0
[0.2.0]: https://https://github.com/internetguru/laravel-feedback/compare/v0.1.5...v0.2.0
[0.2.0-rc.1]: https://github.com/internetguru/laravel-feedback/releases/tag/v0.1.5
[0.1.5]: https://https://github.com/internetguru/laravel-feedback/compare/v0.1.4...v0.1.5
[0.1.4]: https://https://github.com/internetguru/laravel-feedback/compare/v0.1.3...v0.1.4
[0.1.3]: https://https://github.com/internetguru/laravel-feedback/compare/v0.1.2...v0.1.3
[0.1.2]: https://https://github.com/internetguru/laravel-feedback/compare/v0.1.1...v0.1.2
[0.1.1]: https://https://github.com/internetguru/laravel-feedback/compare/v0.1.0...v0.1.1
[0.1.0]: https://https://github.com/internetguru/laravel-feedback/compare/v0.0.0...v0.1.0
[0.1.0-rc.1]: https://github.com/internetguru/laravel-feedback/releases/tag/v0.0.0
[0.0.0]: git log v0.0.0
