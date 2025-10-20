# Leistungsanalyse

[English](../en/PERFORMANCE_ANALYSIS.md) | **Русский** | [Deutsch](../de/PERFORMANCE_ANALYSIS.md) | [Français](../fr/PERFORMANCE_ANALYSIS.md) | [中文](../zh/PERFORMANCE_ANALYSIS.md)

---







**Datum:** Октябрь 2025  
**Version:** 1.1.1

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [Features](features/) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [Tests](tests/) | **PERFORMANCE_ANALYSIS** | [SECURITY_REPORT](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md)

---

## Ergebnisse

### Load Tests
- Light: 55,923 req/sec
- Medium: 54,680 req/sec  
- Heavy: 53,637 req/sec

### Stress Tests
- Max routes: 1,095,000
- Memory/route: 1.39 KB
- Requests: 200k (0 errors)

### Benchmarks
- Add 1000 routes: 3.38ms
- Match first: 121μs
- With parameters: 74μs

### Bewertung: 9/10 ⭐⭐⭐⭐⭐

Details: [PERFORMANCE_BENCHMARK_REPORT.md](tests/PERFORMANCE_BENCHMARK_REPORT.md) | [LOAD_STRESS_REPORT.md](tests/LOAD_STRESS_REPORT.md)

---

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [FAQ](FAQ.md)

**© 2024 CloudCastle HTTP Router**
