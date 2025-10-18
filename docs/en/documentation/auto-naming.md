# Automatic Route Naming

**CloudCastle HTTP Router v1.1.1**  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/auto-naming.md)
- **[English](auto-naming.md)** (current)
- [Deutsch](../../de/documentation/auto-naming.md)
- [Français](../../fr/documentation/auto-naming.md)

---

## 🤖 Introduction

Automatic route naming (Auto-Naming) is a unique CloudCastle Router feature that automatically generates route names based on their URI and HTTP method.

**Default**: Disabled  
**Status**: Stable (v1.1.1)

---

## 🎯 Why Auto-naming?

### Problem

```php
// Without auto-naming - manual naming required
Route::get('/api/v1/users/{id}/posts/{post}', 'Controller@show')
    ->name('api.v1.users.posts.show');

// ... for every route
```

### Solution

```php
// With auto-naming - names generated automatically!
$router->enableAutoNaming();

Route::get('/api/v1/users/{id}/posts/{post}', 'Controller@show');
// Name: api.v1.users.id.posts.post.get
```

---

## 🚀 Usage

### Enable Auto-naming

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->enableAutoNaming();
```

### Disable

```php
$router->disableAutoNaming();
```

### Check Status

```php
if ($router->isAutoNamingEnabled()) {
    echo 'Auto-naming enabled';
}
```

---

## 📐 Generation Rules

### Basic Rules

1. **Slashes** (`/`) → dots (`.`)
2. **Dashes** (`-`) → dots (`.`)
3. **Underscores** (`_`) → dots (`.`)
4. **Parameters** `{param}` → parameter name
5. **HTTP method** → appended at end (lowercase)

### Transformation Examples

| URI | Method | Generated Name |
|-----|--------|----------------|
| `/users` | GET | `users.get` |
| `/users/{id}` | GET | `users.id.get` |
| `/api/v1/users` | GET | `api.v1.users.get` |
| `/api/v1/users/{id}` | POST | `api.v1.users.id.post` |
| `/` | GET | `root.get` |
| `/api-v1/user_profile` | GET | `api.v1.user.profile.get` |

---

## 💡 Usage Examples

### Example 1: Simple Routes

```php
$router->enableAutoNaming();

Route::get('/users', 'UserController@index');
// Name: users.get

Route::get('/posts', 'PostController@index');
// Name: posts.get

// Usage
$url = route('users.get'); // /users
```

### Example 2: With Parameters

```php
$router->enableAutoNaming();

Route::get('/users/{id}', 'UserController@show');
// Name: users.id.get

Route::get('/users/{id}/posts/{post}', 'PostController@show');
// Name: users.id.posts.post.get
```

### Example 3: API Versioning

```php
$router->enableAutoNaming();

Route::get('/api/v1/users', 'Api\V1\UserController@index');
// Name: api.v1.users.get

Route::get('/api/v2/users', 'Api\V2\UserController@index');
// Name: api.v2.users.get
```

---

## 🔧 Working with Groups

```php
$router->enableAutoNaming();

$router->group(['prefix' => 'admin/dashboard'], function(Router $r) {
    $r->get('/users', 'AdminController@users');
    // Name: admin.dashboard.users.get
    // URI: admin/dashboard/users
});
```

---

## ⚙️ Explicit Name Priority

Explicitly set names are NOT overwritten:

```php
$router->enableAutoNaming();

// Automatic name
Route::get('/auto', 'Controller@auto');
// Name: auto.get

// Explicit name - preserved!
Route::get('/manual', 'Controller@manual')
    ->name('my.custom.name');
// Name: my.custom.name (NOT manual.get)
```

---

## 📋 Best Practices

### ✅ When to Use

- Large number of routes (> 50)
- API with versioning
- Consistent URI structure
- RESTful architecture

### ❌ When NOT to Use

- Small projects (< 20 routes)
- Non-standard naming schemes
- Full control over names needed

---

## 🔗 See Also

- [Routes](routes.md)
- [Route Groups](route-groups.md)
- [Examples](../../../examples/auto-naming-example.php)

---

**[← Back to contents](README.md)**

