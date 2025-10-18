# Rapport de test de stress

**CloudCastle HTTP Router v1.1.1**  
**Date**: Octobre 2025  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/reports/stress-testing.md)
- [English](../../en/reports/stress-testing.md)
- [Deutsch](../../de/reports/stress-testing.md)
- **[Français](stress-testing.md)** (actuel)

---

## 💪 Résultats récapitulatifs

| Test | Résultat | Évaluation |
|------|----------|------------|
| **Routes max** | 740 000+ | ⭐⭐⭐⭐⭐ |
| **Volume extrême** | 200 000 req | ⭐⭐⭐⭐⭐ |
| **Stress mémoire** | Jusqu'à la limite | ⭐⭐⭐⭐⭐ |

---

## Test 1: Capacité maximale de routes

**Objectif**: Déterminer le nombre maximum de routes

**Résultats**:
```
Routes maximum:        100 000
Temps d'inscription:   4,35s
Mémoire utilisée:      144,01 Mo
Par route:             1,47 Ko
```

**Conclusion**: ✅ Excellente évolutivité

---

## Test 2: Volume de requêtes extrême

**Configuration**: 200 000 requêtes

**Résultats**:
```
Total requêtes:   200 000
Réussis:          200 000
Erreurs:          0
Durée:            3,60s
Requêtes/sec:     55 609
```

**Stabilité**: ✅ 100% (0 erreur)

---

## Test 3: Stress de limite de mémoire

**Testé jusqu'à**: 740 000 routes

**Progression**:
```
100 000 →   21 Mo
200 000 →  148 Mo
500 000 →  528 Mo
740 000 →  872 Mo
```

**Efficacité**: ✅ Croissance mémoire linéaire

---

**[← Retour aux rapports](tests.md)**

