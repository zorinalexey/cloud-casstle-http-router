# Changelog

**Language**: English

**Translations**: [Русский](../ru/CHANGELOG.md) | [Deutsch](../de/CHANGELOG.md) | [Français](../fr/CHANGELOG.md)

---

## [1.1.0] - 2025-10-16

### Added

#### Auto-Ban System
- `BanManager` class for IP ban management
- `BannedException` - specialized exception
- `Route::throttleWithBan()` - auto-ban method
- Automatic ban expiration
- Ban statistics and monitoring
- Protection from brute-force/DDoS/API abuse

#### Time Units Support
- `TimeUnit` enum for time units
- `RateLimiter::perSecond/Minute/Hour/Day/Week/Month()`
- `Route::perSecond/Minute/Hour/Day/Week/Month()`
- Flexible rate limiting

#### Testing
- 16 new tests for auto-ban system
- 18 new tests for time units
- Total: 245 tests (100% passing)

### Changed
- `RateLimiter` now works with seconds internally
- `Route::throttle()` accepts seconds (backward compatible)
- Code optimized with Rector

### Fixed
- Improved rate limiting performance
- Better handling of time windows

## [1.0.0] - 2025-10-15

### Added
- Basic routing system
- Route groups
- Middleware system
- Named and tagged routes
- IP filtering
- Rate limiting
- Route caching
- 211 unit tests

---

[1.1.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/zorinalexey/cloud-casstle-http-router/releases/tag/v1.0.0
