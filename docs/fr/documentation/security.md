# Sécurité

**CloudCastle HTTP Router v1.1.1**  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/security.md)
- [English](../../en/documentation/security.md)
- [Deutsch](../../de/documentation/security.md)
- **[Français](security.md)** (actuel)

---

## 🛡️ Aperçu de la sécurité

CloudCastle Router fournit une protection complète des applications avec des mécanismes de sécurité intégrés.

**Résultats des tests**: 13/13 tests de sécurité réussis ✅  
**Conformité**: OWASP Top 10 ✅

---

## 🔒 Filtrage IP

### Liste blanche

```php
Route::get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.0/24', '10.0.0.1']);
```

### Liste noire

```php
Route::get('/public', 'PublicController@index')
    ->blacklistIp(['1.2.3.4', '5.6.7.8']);
```

---

## 🔐 HTTPS Enforcement

```php
Route::post('/login', 'AuthController@login')->https();
Route::post('/payment', 'PaymentController@process')->https();
```

---

## 🛡️ Middleware intégrés

### HTTPS Enforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/login', 'AuthController@login')
    ->middleware(new HttpsEnforcement(redirectToHttps: true));
```

### SSRF Protection

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

Route::post('/fetch-url', 'Controller@fetchUrl')
    ->middleware(new SsrfProtection());
```

**Protège contre**:
- ✅ Requêtes vers localhost
- ✅ Requêtes vers IPs privées
- ✅ Requêtes vers endpoints de métadonnées
- ✅ Protocole File://

---

## 🎯 Sécurité maximale

```php
Route::post('/admin/critical', 'AdminController@critical')
    ->https()
    ->whitelistIp(['192.168.1.0/24'])
    ->middleware(['auth', 'admin'])
    ->throttleWithBan(3, 60, 1, 86400);
```

---

## 📊 Tests de sécurité

**13 tests de sécurité réussis**:

✅ Protection Path Traversal  
✅ Protection SQL Injection  
✅ Protection XSS  
✅ Sécurité Whitelist IP  
✅ Conformité OWASP Top 10

[Rapport détaillé →](../reports/security.md)

---

**[← Retour au sommaire](README.md)**

