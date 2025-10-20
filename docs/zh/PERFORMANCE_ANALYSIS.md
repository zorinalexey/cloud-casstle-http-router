# 性能分析

[English](../en/PERFORMANCE_ANALYSIS.md) | [Русский](../ru/PERFORMANCE_ANALYSIS.md) | [Deutsch](../de/PERFORMANCE_ANALYSIS.md) | [Français](../fr/PERFORMANCE_ANALYSIS.md) | [**中文**](PERFORMANCE_ANALYSIS.md)

---

**日期:** 2025年10月  
**版本:** 1.1.1

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [Features](features/) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [Tests](tests/) | **PERFORMANCE_ANALYSIS** | [SECURITY_REPORT](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md)

---

## 结果

### 负载测试
- 轻量: 55,923 req/sec
- 中等: 54,680 req/sec  
- 重量: 53,637 req/sec

### 压力测试
- 最大路由: 1,095,000
- 内存/路由: 1.39 KB
- 请求: 200k (0错误)

### 基准测试
- 添加1000路由: 3.38ms
- 首次匹配: 121μs
- 带参数: 74μs

### 评分: 9/10 ⭐⭐⭐⭐⭐

详情: [PERFORMANCE_BENCHMARK_REPORT.md](tests/PERFORMANCE_BENCHMARK_REPORT.md) | [LOAD_STRESS_REPORT.md](tests/LOAD_STRESS_REPORT.md)

---

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [FAQ](FAQ.md)

**© 2024 CloudCastle HTTP Router**