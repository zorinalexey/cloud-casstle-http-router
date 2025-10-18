# Shortcuts

**CloudCastle HTTP Router v1.1.1**  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/shortcuts.md)
- [English](../../en/documentation/shortcuts.md)
- **[Deutsch](shortcuts.md)** (aktuell)
- [Français](../../fr/documentation/shortcuts.md)

---

## 📋 Einführung

Shortcuts sind praktische Methoden zum schnellen Einrichten typischer Routenkonfigurationen.

---

## 🔧 Verfügbare Shortcuts

### auth() - Authentifizierung erforderlich

```php
Route::get('/dashboard', 'DashboardController@index')->auth();
```

### api() - API-Endpunkt

```php
Route::post('/api/data', 'ApiController@store')->api();
```

### secure() - Sichere Route

```php
Route::post('/payment', 'PaymentController@process')->secure();
```

### admin() - Admin-Route

```php
Route::get('/admin', 'AdminController@index')->admin();
```

---

**[← Zurück zum Inhaltsverzeichnis](README.md)**

