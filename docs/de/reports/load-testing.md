# Lasttestbericht

**CloudCastle HTTP Router v1.1.1**  
**Datum**: September 2025  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/reports/load-testing.md)
- [English](../../en/reports/load-testing.md)
- **[Deutsch](load-testing.md)** (aktuell)
- [Français](../../fr/reports/load-testing.md)

---

## 📊 Zusammenfassungsergebnisse

| Szenario | Routen | Anfragen | Req/sec | Latenz | Speicher |
|----------|--------|----------|---------|--------|----------|
| **Leichte Last** | 100 | 1.000 | **60.095** | 0,02ms | 4 MB |
| **Mittlere Last** | 500 | 5.000 | **58.905** | 0,02ms | 4 MB |
| **Schwere Last** | 1.000 | 10.000 | **59.599** | 0,02ms | 6 MB |

**Gesamtbewertung**: ⭐⭐⭐⭐⭐ **Ausgezeichnet**

---

## Test 1: Leichte Last

**Konfiguration**:
- Routen: 100
- Anfragen: 1.000

**Ergebnisse**:
```
Dauer:          0,0166s
Anfragen/sec:   60.095
Avg. Zeit:      0,02ms
Speicher:       4,00 MB
```

---

## 📈 Leistungsdiagramm

```
Leicht  ████████████████████ 60.095 req/s
Mittel  ███████████████████░ 58.905 req/s
Schwer  ███████████████████░ 59.599 req/s
```

---

## ✅ Schlussfolgerungen

**Stabilität**: 100%  
**Skalierbarkeit**: Ausgezeichnet  
**Leistung**: 60k+ req/s

---

**[← Zurück zu Berichten](tests.md)**

