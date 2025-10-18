# Performance

**CloudCastle HTTP Router v1.1.1**  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/performance.md)
- [English](../../en/documentation/performance.md)
- [Deutsch](../../de/documentation/performance.md)
- **[Français](performance.md)** (actuel)

---

## ⚡ Métriques clés

| Métrique | Valeur |
|----------|--------|
| **Vitesse** | 60 095 req/s |
| **Routes max** | 740 000+ |
| **Mémoire/route** | 1,47 Ko |
| **Recherche** | O(1) |
| **Latence** | 0,02 ms |

---

## 🚀 Optimisation

### 1. Mise en cache des routes

```php
$router = Router::getInstance();
$router->enableCache('cache/routes.php');
$router->compile();
```

**Effet**: Chargement 50% plus rapide pour > 100 routes

### 2. Indexation

CloudCastle indexe automatiquement les routes pour une recherche O(1)

---

## 📊 Benchmarks

### Enregistrement de routes

| Quantité | Temps | Routes/sec |
|----------|-------|------------|
| 1 000 | 0,05s | 20 000 |
| 10 000 | 0,50s | 20 000 |
| 100 000 | 4,35s | 22 988 |

---

**[← Retour au sommaire](README.md)**

