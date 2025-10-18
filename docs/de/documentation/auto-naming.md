# Automatische Routenbenennung

**CloudCastle HTTP Router v1.1.1**  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/auto-naming.md)
- [English](../../en/documentation/auto-naming.md)
- **[Deutsch](auto-naming.md)** (aktuell)
- [Français](../../fr/documentation/auto-naming.md)

---

## 🤖 Einführung

Automatische Routenbenennung (Auto-Naming) ist eine einzigartige CloudCastle Router-Funktion, die automatisch Routennamen basierend auf URI und HTTP-Methode generiert.

**Standard**: Deaktiviert  
**Status**: Stabil (v1.1.1)

---

## 🚀 Verwendung

### Auto-Naming aktivieren

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->enableAutoNaming();
```

### Deaktivieren

```php
$router->disableAutoNaming();
```

---

## 📐 Generierungsregeln

### Grundregeln

1. **Schrägstriche** (`/`) → Punkte (`.`)
2. **Bindestriche** (`-`) → Punkte (`.`)
3. **Unterstriche** (`_`) → Punkte (`.`)
4. **Parameter** `{param}` → Parametername
5. **HTTP-Methode** → am Ende hinzugefügt (Kleinbuchstaben)

### Transformationsbeispiele

| URI | Methode | Generierter Name |
|-----|---------|------------------|
| `/users` | GET | `users.get` |
| `/users/{id}` | GET | `users.id.get` |
| `/api/v1/users` | GET | `api.v1.users.get` |
| `/` | GET | `root.get` |

---

## 💡 Verwendungsbeispiele

### Beispiel 1: Einfache Routen

```php
$router->enableAutoNaming();

Route::get('/users', 'UserController@index');
// Name: users.get

Route::get('/posts', 'PostController@index');
// Name: posts.get
```

### Beispiel 2: API-Versionierung

```php
$router->enableAutoNaming();

Route::get('/api/v1/users', 'Api\V1\UserController@index');
// Name: api.v1.users.get

Route::get('/api/v2/users', 'Api\V2\UserController@index');
// Name: api.v2.users.get
```

---

## 🔧 Arbeiten mit Gruppen

```php
$router->enableAutoNaming();

$router->group(['prefix' => 'admin/dashboard'], function(Router $r) {
    $r->get('/users', 'AdminController@users');
    // Name: admin.dashboard.users.get
});
```

---

## 🎯 Vorteile

- ✅ Zeitersparnis
- ✅ Konsistenz
- ✅ Vorhersagbarkeit
- ✅ Flexibilität

---

## 🔗 Siehe auch

- [Routen](routes.md)
- [Beispiele](../../../examples/auto-naming-example.php)

---

**[← Zurück zum Inhaltsverzeichnis](README.md)**

