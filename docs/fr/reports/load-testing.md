# Rapport de test de charge

**CloudCastle HTTP Router v1.1.1**  
**Date**: Septembre 2025  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/reports/load-testing.md)
- [English](../../en/reports/load-testing.md)
- [Deutsch](../../de/reports/load-testing.md)
- **[Français](load-testing.md)** (actuel)

---

## 📊 Résultats récapitulatifs

| Scénario | Routes | Requêtes | Req/sec | Latence | Mémoire |
|----------|--------|----------|---------|---------|---------|
| **Charge légère** | 100 | 1 000 | **60 095** | 0,02ms | 4 Mo |
| **Charge moyenne** | 500 | 5 000 | **58 905** | 0,02ms | 4 Mo |
| **Charge lourde** | 1 000 | 10 000 | **59 599** | 0,02ms | 6 Mo |

**Évaluation globale**: ⭐⭐⭐⭐⭐ **Excellent**

---

## Test 1: Charge légère

**Configuration**:
- Routes: 100
- Requêtes: 1 000

**Résultats**:
```
Durée:           0,0166s
Requêtes/sec:    60 095
Temps moy.:      0,02ms
Mémoire:         4,00 Mo
```

---

## Test 2: Charge moyenne

**Configuration**:
- Routes: 500
- Requêtes: 5 000

**Résultats**:
```
Durée:           0,0849s
Requêtes/sec:    58 905
Temps moy.:      0,02ms
Mémoire:         4,00 Mo
```

---

## Test 3: Charge lourde

**Configuration**:
- Routes: 1 000
- Requêtes: 10 000

**Résultats**:
```
Durée:           0,1678s
Requêtes/sec:    59 599
Temps moy.:      0,02ms
Mémoire:         6,00 Mo
```

---

## 📈 Graphique de performance

```
Légère  ████████████████████ 60 095 req/s
Moyenne ███████████████████░ 58 905 req/s
Lourde  ███████████████████░ 59 599 req/s
```

---

## ✅ Conclusions

**Stabilité**: 100%  
**Évolutivité**: Excellente  
**Performance**: 60k+ req/s

---

**[← Retour aux rapports](tests.md)**

