# Load Testing Report

**CloudCastle HTTP Router v1.1.1**  
**Date**: October 2025  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/reports/load-testing.md)
- **[English](load-testing.md)** (current)
- [Deutsch](../../de/reports/load-testing.md)
- [Français](../../fr/reports/load-testing.md)

---

## 📊 Summary Results

| Scenario | Routes | Requests | Req/sec | Latency | Memory |
|----------|--------|----------|---------|---------|--------|
| **Light Load** | 100 | 1,000 | **60,095** | 0.02ms | 4 MB |
| **Medium Load** | 500 | 5,000 | **58,905** | 0.02ms | 4 MB |
| **Heavy Load** | 1,000 | 10,000 | **59,599** | 0.02ms | 6 MB |

**Overall Rating**: ⭐⭐⭐⭐⭐ **Excellent**

---

## Test 1: Light Load

**Configuration**:
- Routes: 100
- Requests: 1,000

**Results**:
```
Duration:          0.0166s
Requests/sec:      60,095
Avg time:          0.02ms
Memory:            4.00 MB
```

---

## Test 2: Medium Load

**Configuration**:
- Routes: 500
- Requests: 5,000

**Results**:
```
Duration:          0.0849s
Requests/sec:      58,905
Avg time:          0.02ms
Memory:            4.00 MB
```

---

## Test 3: Heavy Load

**Configuration**:
- Routes: 1,000
- Requests: 10,000

**Results**:
```
Duration:          0.1678s
Requests/sec:      59,599
Avg time:          0.02ms
Memory:            6.00 MB
```

---

## 📈 Performance Chart

```
Light   ████████████████████ 60,095 req/s
Medium  ███████████████████░ 58,905 req/s
Heavy   ███████████████████░ 59,599 req/s
```

---

## ✅ Conclusions

**Stability**: 100%  
**Scalability**: Excellent  
**Performance**: 60k+ req/s

---

**[← Back to reports](tests.md)**

