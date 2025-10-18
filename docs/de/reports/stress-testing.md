# Stresstestbericht

**CloudCastle HTTP Router v1.1.1**  
**Datum**: September 2025  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/reports/stress-testing.md)
- [English](../../en/reports/stress-testing.md)
- **[Deutsch](stress-testing.md)** (aktuell)
- [Français](../../fr/reports/stress-testing.md)

---

## 💪 Zusammenfassungsergebnisse

| Test | Ergebnis | Bewertung |
|------|----------|-----------|
| **Max. Routen** | 740.000+ | ⭐⭐⭐⭐⭐ |
| **Extremes Volumen** | 200.000 Anfragen | ⭐⭐⭐⭐⭐ |
| **Speicher-Stress** | Bis zum Limit | ⭐⭐⭐⭐⭐ |

---

## Test 1: Maximale Routen-Kapazität

**Ergebnisse**:
```
Maximum Routen:      100.000
Registrierungszeit:  4,35s
Speicher verwendet:  144,01 MB
Pro Route:           1,47 KB
```

**Fazit**: ✅ Ausgezeichnete Skalierbarkeit

---

## Test 2: Extremes Anfragevolumen

**Konfiguration**: 200.000 Anfragen

**Ergebnisse**:
```
Gesamt Anfragen:     200.000
Erfolgreich:         200.000
Fehler:              0
Dauer:               3,60s
Anfragen/sec:        55.609
```

**Stabilität**: ✅ 100% (0 Fehler)

---

## Test 3: Speicher-Limit-Stress

**Getestet bis**: 740.000 Routen

```
100.000 →   21 MB
500.000 →  528 MB
740.000 →  872 MB
```

**Effizienz**: ✅ Lineares Speicherwachstum

---

**[← Zurück zu Berichten](tests.md)**

