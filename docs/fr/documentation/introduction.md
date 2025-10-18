# Introduction

**CloudCastle HTTP Router v1.1.1**  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/introduction.md)
- [English](../../en/documentation/introduction.md)
- [Deutsch](../../de/documentation/introduction.md)
- **[Français](introduction.md)** (actuel)

---

## À propos du projet

**CloudCastle HTTP Router** est un routeur HTTP haute performance pour PHP 8.2+, conçu avec un focus sur la performance, la sécurité et la facilité d'utilisation.

### Philosophie du projet

Nous avons créé un routeur qui combine:
- **Vitesse** - 60 000+ requêtes par seconde
- **Évolutivité** - support de 740 000+ routes
- **Sécurité** - protection intégrée contre les attaques
- **Commodité** - API intuitive et automatisation

---

## ✨ Fonctionnalités clés

### Routage
- ✅ Toutes les méthodes HTTP (GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD)
- ✅ Paramètres dynamiques avec contraintes regex
- ✅ Groupes de routes avec préfixes
- ✅ Routes nommées
- ✅ Routes taguées
- ✅ **Nommage automatique des routes** 🆕
- ✅ Groupes imbriqués
- ✅ Mise en cache des routes

### Sécurité
- 🛡️ **Rate Limiting** avec fenêtres temporelles flexibles
- 🚫 **Auto-ban** en cas de dépassement des limites
- 🔒 **Filtrage IP** (listes blanches/noires)
- 🌐 **Restrictions de domaine**
- 🔐 **Restrictions de protocole** (HTTP/HTTPS/WS/WSS)
- 🛡️ Middleware **HTTPS Enforcement**
- 🛡️ Middleware **SSRF Protection**
- 📝 Middleware **Security Logging**
- ✅ Conformité **OWASP Top 10**

### Performance
- 🚀 **60 000+ req/s** sur charge légère
- 📊 Complexité de recherche **O(1)**
- 💾 **1,47 Ko** de mémoire par route
- ⚡ Compilation et mise en cache
- 🎯 Indexation pour recherche rapide

---

## 🎯 Pour qui ce routeur est-il destiné?

### Parfait pour:

✅ **Applications haute charge** - quand la performance compte  
✅ **Services API** - avec rate limiting et protection intégrés  
✅ **Microservices** - léger et autonome  
✅ **Projets d'entreprise** - avec des exigences de qualité élevées  
✅ **Projets avec nombreuses routes** - évolutivité  

---

## 📦 Installation

### Exigences

- **PHP**: 8.2, 8.3 ou 8.4
- **Composer**: 2.x
- **Extensions**: mbstring, json

### Installation via Composer

```bash
composer require cloudcastle/http-router
```

---

## 🚀 Démarrage rapide

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', fn() => 'Liste des utilisateurs');
Route::post('/users', fn() => 'Utilisateur créé');

$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $result;
```

---

## 📊 Performance

### Benchmarks

| Charge | Req/sec | Mémoire |
|--------|---------|---------|
| Légère (100 routes) | 60 095 | 4 Mo |
| Moyenne (500 routes) | 58 905 | 4 Mo |
| Lourde (1 000 routes) | 59 599 | 6 Mo |

---

## 🛡️ Sécurité

### Protection intégrée

- **Rate Limiting**: De quelques secondes à des mois
- **Auto-Ban**: Blocage automatique en cas de violations
- **Filtrage IP**: Listes blanches et noires
- **HTTPS Enforcement**: Utilisation forcée de HTTPS
- **SSRF Protection**: Protection contre les attaques SSRF

### Tests de sécurité

✅ 13/13 tests de sécurité réussis  
✅ Conformité OWASP Top 10  
✅ Protection: XSS, SQL Injection, Path Traversal, ReDoS  

---

## 📚 Documentation

### Sujets principaux

1. [Démarrage rapide](quickstart.md)
2. [Routes](routes.md)
3. [Auto-naming](auto-naming.md) 🆕
4. [Groupes de routes](route-groups.md)
5. [Middleware](middleware.md)
6. [Rate Limiting](rate-limiting.md)
7. [Auto-ban](auto-ban.md)
8. [Sécurité](security.md)
9. [Performance](performance.md)
10. [Référence API](api-reference.md)

---

**CloudCastle HTTP Router** - Votre choix pour un routage haute performance! 🚀

**[← Retour au sommaire](README.md)**

