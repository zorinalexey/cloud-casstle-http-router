# Auto-ban

**CloudCastle HTTP Router v1.1.1**  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/auto-ban.md)
- [English](../../en/documentation/auto-ban.md)
- [Deutsch](../../de/documentation/auto-ban.md)
- **[Français](auto-ban.md)** (actuel)

---

## 🚫 Introduction

Le système d'auto-ban est une fonctionnalité unique de CloudCastle Router pour protéger contre les attaques brute-force, DDoS et autres abus.

---

## 🎯 Utilisation de base

### Auto-ban simple

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,           // 5 tentatives
        decaySeconds: 60,          // en 60 secondes
        maxViolations: 3,          // 3 violations jusqu'au ban
        banDurationSeconds: 7200   // ban pour 2 heures
    );
```

### Comment ça fonctionne

1. L'utilisateur fait **5 tentatives échouées** en **60 secondes**
2. Cela compte comme **1 violation**
3. Après **3 violations** - l'IP est **bloquée pour 2 heures**

---

## 💡 Exemples d'utilisation

### Protection du login

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(5, 60, 3, 7200);
```

### Protection API

```php
Route::post('/api/data', 'ApiController@store')
    ->throttleWithBan(100, 60, 5, 3600);
```

---

## 🔧 Gestion des bans

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager();

// Vérifier le ban
if ($banManager->isBanned('192.168.1.100')) {
    echo 'IP bannie';
}

// Ban manuel
$banManager->ban('192.168.1.100', 3600);

// Débannir
$banManager->unban('192.168.1.100');
```

---

**[← Retour au sommaire](README.md)**

