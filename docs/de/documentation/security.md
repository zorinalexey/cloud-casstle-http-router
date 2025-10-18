# Sicherheit

**CloudCastle HTTP Router v1.1.1**  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/security.md)
- [English](../../en/documentation/security.md)
- **[Deutsch](security.md)** (aktuell)
- [Français](../../fr/documentation/security.md)

---

## 🛡️ Sicherheitsübersicht

CloudCastle Router bietet umfassenden Anwendungsschutz mit eingebauten Sicherheitsmechanismen.

**Testergebnisse**: 13/13 Sicherheitstests bestanden ✅  
**Konformität**: OWASP Top 10 ✅

---

## 🔒 IP-Filterung

### Whitelist (Weiße Liste)

```php
Route::get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.0/24', '10.0.0.1']);
```

**Unterstützte Formate**:
- Einzelne IP: `192.168.1.100`
- CIDR-Notation: `192.168.1.0/24`
- Array von IPs: `['10.0.0.1', '10.0.0.2']`

### Blacklist (Schwarze Liste)

```php
Route::get('/public', 'PublicController@index')
    ->blacklistIp(['1.2.3.4', '5.6.7.8']);
```

---

## 🔐 HTTPS Enforcement

### HTTPS erzwingen

```php
Route::post('/login', 'AuthController@login')->https();
Route::post('/payment', 'PaymentController@process')->https();
```

### HTTPS-Middleware

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/secure', 'Controller@secure')
    ->middleware(new HttpsEnforcement(redirectToHttps: true));
```

---

## 🛡️ Eingebaute Middleware

### 1. HTTPS Enforcement

```php
Route::post('/login', 'AuthController@login')
    ->middleware(new HttpsEnforcement(redirectToHttps: true));
```

### 2. SSRF Protection

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

Route::post('/fetch-url', 'Controller@fetchUrl')
    ->middleware(new SsrfProtection());
```

**Schützt vor**:
- ✅ Anfragen an localhost
- ✅ Anfragen an private IPs
- ✅ Anfragen an Metadata-Endpoints
- ✅ File://-Protokoll

### 3. Security Logger

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

Route::post('/sensitive', 'Controller@sensitive')
    ->middleware(new SecurityLogger('/var/log/security.log'));
```

---

## 🎯 Maximale Sicherheit

```php
Route::post('/admin/critical', 'AdminController@critical')
    ->https()
    ->whitelistIp(['192.168.1.0/24'])
    ->middleware(['auth', 'admin', '2fa'])
    ->throttleWithBan(3, 60, 1, 86400);
```

---

## 📊 Sicherheitstests

**13 Sicherheitstests bestanden**:

✅ Path Traversal Protection  
✅ SQL Injection Protection  
✅ XSS Protection  
✅ IP Whitelist Security  
✅ OWASP Top 10 Compliance

[Detaillierter Bericht →](../../reports/security.md)

---

**[← Zurück zum Inhaltsverzeichnis](README.md)**

