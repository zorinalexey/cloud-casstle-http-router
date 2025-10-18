# Rapport PHPMD

**CloudCastle HTTP Router v1.1.1**  
**Date**: Septembre 2025  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/reports/phpmd.md)
- [English](../../en/reports/phpmd.md)
- [Deutsch](../../de/reports/phpmd.md)
- **[Français](phpmd.md)** (actuel)

---

## 📊 Résultats finaux

**Rule Sets**: cleancode, codesize, controversial, design, naming, unusedcode

**Violations**:
- **Critiques**: **0**
- **Erreurs**: 9
- **Avertissements**: 9
- **Informations**: 3

**Statut**: ✅ **Bon** (aucun problème critique)

---

## 📈 Métriques du code

### Taille

```
LOC:                            4,148
LLOC:                           2,627
Classes:                        25
Methods:                        279
```

### Complexité

```
Cyclomatic complexity (avg):    16.04
Weighted method count (avg):    26.2
Relative complexity (avg):      439.52
```

### Bugs (prédiction Halstead)

```
Bugs moyens par classe:         0.33
Défauts (Halstead):             0.98
```

---

## 🔍 Violations par catégorie

### Code Size (9 avertissements)

**Router.php**:
- ExcessiveClassLength: 1,520 lignes
- TooManyMethods: 58 méthodes
- ExcessiveClassComplexity: 231

**Criticité**: ⚠️ Basse (classe principale)

### Clean Code (8 erreurs)

- BooleanArgumentFlag: 3
- ElseExpression: 3
- StaticAccess: ~20

**Criticité**: ⚠️ Basse (patterns acceptés)

### Naming (3 avertissements)

- ShortVariable (`$ip`): 15 occurrences

**Criticité**: ✅ Pas un problème (abréviation acceptée)

---

## ✅ Évaluation

```
Code Organization  ████████████████░░░░  80%
Complexity         ████████████░░░░░░░░  60%
Best Practices     ███████████████████░  95%
```

**Total**: **8.5/10** ⭐⭐⭐⭐

---

**[← Retour aux rapports](static-analysis.md)**

