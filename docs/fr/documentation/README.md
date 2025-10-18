# CloudCastle HTTP Router - Documentation

**Version**: 1.1.1  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/README.md) (Documentation complète)
- [English](../../en/documentation/README.md)
- [Deutsch](../../de/documentation/README.md)
- **[Français](README.md)** (actuel)

---

## 📚 Documentation

**Note**: La documentation complète est actuellement disponible en russe. La traduction française est en cours.

Pour la documentation complète, veuillez consulter:
- **[Documentation russe](../../ru/documentation/README.md)** (Complète)

---

## 🚀 Démarrage rapide

```php
<?php

use CloudCastle\Http\Router\Facade\Route;

// Route simple
Route::get('/users', fn() => 'User list');

// Avec paramètres
Route::get('/user/{id}', fn($id) => "User: $id");

// Avec limitation de débit et auto-ban
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 7200
    );

// Dispatch
$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

---

## ✨ Fonctionnalités principales

- ✅ Toutes les méthodes HTTP (GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD)
- ✅ Paramètres dynamiques avec contraintes
- ✅ Groupes de routes avec attributs partagés
- ✅ Routes nommées et taguées
- ✅ **Nommage automatique des routes** 🆕
- ✅ Expressions régulières
- ✅ Mise en cache des routes
- 🛡️ **Auto-ban** - protection contre le brute-force et le DDoS
- ⏱️ **Fenêtres temporelles flexibles** - de quelques secondes à des mois
- 🔒 Filtrage IP (listes blanches/noires)
- 🚀 **60 000+ req/s** de performance
- 📊 **740 000+ routes** supportées

---

## 📊 Résultats des tests

### Tests unitaires
- **263 tests** - tous réussis ✅
- **611 assertions**
- **Couverture**: ~95%

### Performance
- **Charge légère**: 60 095 req/s
- **Charge lourde**: 59 599 req/s
- **Mémoire**: 1,47 Ko par route

### Analyse statique
- **PHPStan**: Niveau MAX - 0 erreur ✅
- **PHPCS**: PSR-12 - 0 erreur ✅

---

## 📦 Installation

```bash
composer require cloudcastle/http-router
```

**Exigences**:
- PHP 8.2, 8.3 ou 8.4
- Composer 2.x

---

## 🆚 Comparaison avec les alternatives

| Fonctionnalité | CloudCastle | FastRoute | Symfony | Laravel |
|----------------|-------------|-----------|---------|---------|
| **Performance** | **60k req/s** | 50k | 30k | 25k |
| **Routes max** | **740k+** | 100k | 50k | 30k |
| **Mémoire/route** | **1,47 Ko** | 2,5 Ko | 3,8 Ko | 4,2 Ko |
| **Rate Limiting** | ✅ Intégré | ❌ | ❌ | ✅ Package |
| **Auto-ban** | ✅ | ❌ | ❌ | ❌ |
| **Auto-naming** | ✅ | ❌ | ❌ | ❌ |

---

## 🔗 Liens

- **GitHub**: https://github.com/zorinalexey/cloud-casstle-http-router
- **Packagist**: https://packagist.org/packages/cloudcastle/http-router
- **Chat de support**: [Telegram](https://t.me/cloud_castle_news)
- **Email**: zorinalexey59292@gmail.com

---

**CloudCastle HTTP Router** - Performance. Sécurité. Simplicité.

**Langue**: Français | [Русский](../../ru/documentation/README.md) | [English](../../en/documentation/README.md) | [Deutsch](../../de/documentation/README.md)

