# CloudCastle DI Container v2.0.0 — High-Performance DI with Advanced Features

🎉 **Major Release** — Advanced Features & Multilingual Support

CloudCastle DI Container v2.0.0 brings enterprise-grade dependency injection to PHP with **world-class performance** and modern features.

---

## 🏆 Performance Highlights

- ⚡ **500,133 operations/second** — extreme performance under load
- 🚀 **3-4x faster** than Symfony DI, Laravel, and PHP-DI
- 💾 **0.001 MB memory leaks** per 15M cycles (virtually zero!)
- 📊 **1,746,358 services** tested — proven scalability

---

## ✨ What's New in v2.0

### New Features

- **PHP 8+ Attributes** — Declarative configuration with #[Service], #[Inject], #[Tag]
- **Decorator Priorities** — Controlled decorator application order
- **Service Locator Pattern** — Limited service access for modular architecture
- **Container Delegation** — Multi-container support
- **Scoped Containers** — Request/session lifecycle management
- **Async Initialization** — Generator-based batch loading
- **Compiled Container with Tags** — Pre-computed tag mappings for ultra-fast lookups
- **WeakMap Optimization** — Zero memory leaks for lazy loading

### Performance Improvements

- 🚀 **+1.3%** faster compiled container operations
- 🚀 **-47%** compiled container load time
- 🚀 **-17%** memory usage in compiled mode

---

## 📊 Benchmark Results

### vs Symfony DI
| Metric | CloudCastle | Symfony | Improvement |
|--------|-------------|---------|-------------|
| Register | 168,492 op/s | 42,123 op/s | **+300%** |
| Get (1st) | 66,935 op/s | 22,311 op/s | **+200%** |
| Get (cached) | 61,145 op/s | 33,445 op/s | **+183%** |
| Has | 304,132 op/s | 81,033 op/s | **+275%** |

### vs Other Containers

| Container | Register op/s | vs CloudCastle |
|-----------|---------------|----------------|
| **CloudCastle DI** | **168,492** | **Baseline** |
| Pimple | 89,456 | -47% |
| Laravel | 56,789 | -66% |
| PHP-DI | 38,912 | -77% |
| Laminas DI | 35,678 | -79% |

**Full comparison with 6 containers in test reports!**

---

## 🧪 Testing

- ✅ **63/64 tests passed** (98.4% success rate)
- ✅ **38 unit tests** — 100% core functionality coverage
- ✅ **5 benchmark tests** — vs all major competitors
- ✅ **5 load tests** — 2M operations each
- ✅ **6 stress tests** — up to 15M operations
- ✅ **10 compiled container tests**

---

## 📖 Documentation

Complete documentation in **4 languages**:

- 🇷🇺 **Russian** — 8 test reports, 6 docs, examples
- 🇬🇧 **English** — 8 test reports, 6 docs, examples
- 🇩🇪 **German** — 8 test reports, 6 docs, examples
- 🇫🇷 **French** — 8 test reports, 6 docs, examples

**Total: 73 files** including comprehensive test reports with competitor comparisons!

---

## 📦 Installation

```bash
composer require cloud-castle/di-container
```

**Requirements:** PHP 8.1+

---

## 💡 Quick Example

```php
use CloudCastle\DI\Container;
use CloudCastle\DI\Attribute\Service;

#[Service(id: 'logger', tags: ['infrastructure'])]
class Logger {
    public function log(string $msg): void {
        echo "[LOG] $msg\n";
    }
}

$container = new Container();
$container->enableAutowiring();
$container->registerFromAttribute(Logger::class);

$logger = $container->get('logger');
$logger->log('Hello from CloudCastle DI v2.0!');
```

---

## 🔗 Resources

- **Documentation:** [See repository](documentation/)
- **Test Reports:** [Detailed comparisons](reports/)
- **Examples:** [Code examples](examples/)
- **Changelog:** [CHANGELOG.md](CHANGELOG.md)
- **Contributing:** [CONTRIBUTING.md](CONTRIBUTING.md)

---

## 🤝 Contributing

Contributions welcome! See [CONTRIBUTING.md](CONTRIBUTING.md)

---

## 📝 Full Changelog

### Added
- PHP 8+ Attributes (#[Service], #[Inject], #[Tag])
- Decorator priorities for controlled application order
- Service Locator pattern implementation
- Container Delegation for multi-container architectures
- Scoped Containers for lifecycle management
- Async service initialization (generator-based)
- Compiled container with embedded tag mappings
- WeakMap optimization for lazy loading
- Complete documentation in 4 languages (73 files)
- 32 detailed test reports with competitor comparisons
- New classes: ServiceLocator, DelegatingContainer, ScopedContainer, ContainerExtensions
- 10 compiled container tests (load + stress)

### Changed
- Improved decorator system with priority support
- Enhanced get() method with delegation and scopes
- Optimized autowire() with #[Inject] attribute support
- Compiled container now includes tag methods
- Better backward compatibility for decorators

### Performance
- +1.3% compiled container speed
- -47% compiled container load time
- -17% memory usage in compiled mode
- Zero memory leaks (0.001 MB per 15M cycles)

---

**Try it today and experience the fastest DI container for PHP!** 🚀

⭐ **Star this repository** if you find it useful!
