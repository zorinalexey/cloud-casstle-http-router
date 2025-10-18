# Nommage automatique des routes

**CloudCastle HTTP Router v1.1.1**  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/auto-naming.md)
- [English](../../en/documentation/auto-naming.md)
- [Deutsch](../../de/documentation/auto-naming.md)
- **[Français](auto-naming.md)** (actuel)

---

## 🤖 Introduction

Le nommage automatique des routes (Auto-Naming) est une fonctionnalité unique de CloudCastle Router qui génère automatiquement des noms de routes basés sur leur URI et leur méthode HTTP.

**Par défaut**: Désactivé  
**Statut**: Stable (v1.1.1)

---

## 🚀 Utilisation

### Activer l'auto-naming

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->enableAutoNaming();
```

### Désactiver

```php
$router->disableAutoNaming();
```

---

## 📐 Règles de génération

### Règles de base

1. **Barres obliques** (`/`) → points (`.`)
2. **Tirets** (`-`) → points (`.`)
3. **Traits de soulignement** (`_`) → points (`.`)
4. **Paramètres** `{param}` → nom du paramètre
5. **Méthode HTTP** → ajoutée à la fin (minuscules)

### Exemples de transformation

| URI | Méthode | Nom généré |
|-----|---------|------------|
| `/users` | GET | `users.get` |
| `/users/{id}` | GET | `users.id.get` |
| `/api/v1/users` | GET | `api.v1.users.get` |
| `/` | GET | `root.get` |

---

## 💡 Exemples d'utilisation

### Exemple 1: Routes simples

```php
$router->enableAutoNaming();

Route::get('/users', 'UserController@index');
// Nom: users.get

Route::get('/posts', 'PostController@index');
// Nom: posts.get
```

### Exemple 2: Versioning API

```php
$router->enableAutoNaming();

Route::get('/api/v1/users', 'Api\V1\UserController@index');
// Nom: api.v1.users.get

Route::get('/api/v2/users', 'Api\V2\UserController@index');
// Nom: api.v2.users.get
```

---

## 🔧 Travailler avec les groupes

```php
$router->enableAutoNaming();

$router->group(['prefix' => 'admin/dashboard'], function(Router $r) {
    $r->get('/users', 'AdminController@users');
    // Nom: admin.dashboard.users.get
});
```

---

## ⚙️ Priorité des noms explicites

Les noms explicitement définis ne sont PAS écrasés:

```php
$router->enableAutoNaming();

// Nom automatique
Route::get('/auto', 'Controller@auto');
// Nom: auto.get

// Nom explicite - conservé!
Route::get('/manual', 'Controller@manual')
    ->name('my.custom.name');
// Nom: my.custom.name
```

---

## 🎯 Avantages

- ✅ Gain de temps
- ✅ Cohérence
- ✅ Prévisibilité
- ✅ Flexibilité

---

## 🔗 Voir aussi

- [Routes](routes.md)
- [Exemples](../../../examples/auto-naming-example.php)

---

**[← Retour au sommaire](README.md)**

