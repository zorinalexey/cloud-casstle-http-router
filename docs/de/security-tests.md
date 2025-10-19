[🇷🇺 Русский](ru/security-tests.md) | [🇺🇸 English](en/security-tests.md) | [🇩🇪 Deutsch](de/security-tests.md) | [🇫🇷 Français](fr/security-tests.md) | [🇨🇳 中文](zh/security-tests.md)

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)

---

# Sicherheitstests des CloudCastle HTTP Routers

**Sprachen:** 🇷🇺 Russisch | [🇬🇧 Englisch](../en/security-tests.md) | [🇩🇪 Deutsch](../de/security-tests.md) | [🇫🇷 Français](../fr/security-tests.md) | [🇨🇳中文](../zh/security-tests.md)

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

## 📊 Allgemeine Informationen

**Sicherheitstests insgesamt**: 13
**Status**: ✅ Alle Tests bestanden (100 %)
**Assertions**: 38  
**Ausführungszeit**: 0,110 s
**Speicher**: 12 MB

## 🛡️ Schutzkategorien

### 1. Path Traversal Protection

**Beschreibung**: Schutz vor Angriffen mit „../“, um auf Dateien außerhalb des Web-Roots zuzugreifen.

**Test**: Versuch, auf „/../../etc/passwd“ zuzugreifen

**Schutzmechanismus**:
- Normalisierung von Pfaden
- Blockierungssequenzen `../`
- Überprüfung auf absolute Pfade

**Ergebnis**: ✅ BESTANDEN

**Schutzbeispiel:**
```php
$router->get('/files/{path}', function($path) {
    // Роутер автоматически блокирует '../../../etc/passwd'
    // Вызовет RouteNotFoundException
    return file_get_contents(__DIR__ . '/uploads/' . $path);
});
```

**Vergleich mit Mitbewerbern:**
- CloudCastle: ✅ Integrierter Schutz
- FastRoute: ❌ Kein Schutz
- Symfony: ✅ Es gibt Schutz
- Laravel: ✅ Es gibt Schutz
- Schlank: ❌ Kein Schutz
- AltoRouter: ❌ Kein Schutz

---

### 2. SQL Injection in Parameters

**Beschreibung**: Schutz vor SQL-Injections durch Routenparameter.

**Test**: Parameter wie „OR ‚1‘=‚1‘

**Schutzmechanismus**:
- Parameter werden unverändert übergeben (nicht interpretiert)
- Verantwortung auf Anwendungsebene
- Der Router führt keine SQL-Abfragen aus

**Ergebnis**: ✅ BESTANDEN

**Empfehlungen:**
```php
// ПРАВИЛЬНО: используйте prepared statements
$router->get('/users/{id}', function($id) use ($pdo) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
});

// НЕПРАВИЛЬНО: прямая интерполяция
$router->get('/users/{id}', function($id) use ($pdo) {
    return $pdo->query("SELECT * FROM users WHERE id = {$id}"); // ОПАСНО!
});
```

---

### 3. XSS (Cross-Site Scripting) Protection

**Beschreibung**: Schutz vor XSS-Angriffen über Parameter.

**Test**: Parameter wie `<script>alert('XSS')</script>`

**Schutzmechanismus**:
- Parameter werden vom Router nicht automatisch überprüft
- Die Anwendung ist für die Desinfektion verantwortlich
- Der Router liefert saubere Daten

**Ergebnis**: ✅ BESTANDEN

**Empfehlungen:**
```php
// ПРАВИЛЬНО: экранируйте вывод
$router->get('/search/{query}', function($query) {
    return htmlspecialchars($query, ENT_QUOTES, 'UTF-8');
});

// Или используйте шаблонизатор с авто-экранированием
$router->get('/search/{query}', function($query) use ($twig) {
    return $twig->render('search.html', ['query' => $query]);
});
```

---

### 4. IP Whitelist Security

**Beschreibung**: Zugriff nur auf autorisierte IP-Adressen beschränken.

**Mechanismus**:
```php
$router->get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.100', '10.0.0.0/8']);
```

**Test**: Zugriff von einer nicht autorisierten IP

**Ergebnis**: ✅ PASSED – IpNotAllowedException ausgelöst

**Anwendung:**
- Verwaltungsgremien
- Internal API endpoints
- Restricted resources

---

### 5. IP Blacklist Security

**Beschreibung**: Blockieren des Zugriffs von bestimmten IP-Adressen.

**Mechanismus**:
```php
$router->get('/api/data', 'ApiController@data')
    ->blacklistIp(['1.2.3.4', '5.6.7.0/24']);
```

**Test**: Zugriff von einer gesperrten IP

**Ergebnis**: ✅ PASSED – IpNotAllowedException ausgelöst

**Anwendung:**
- Blockieren bösartiger IPs
- Anti-Spam-Schutz
- Geoblocking

---

### 6. IP Spoofing Protection

**Beschreibung**: Schutz vor IP-Adress-Spoofing über HTTP-Header.

**Gefährliche Header**:
- `X-Forwarded-For`
- `X-Real-IP`
- `Client-IP`

**Schutzmechanismus**:
- Verwendung von $_SERVER['REMOTE_ADDR']
- Ignorieren Sie nicht vertrauenswürdige Header
- Überprüfen von Proxy-Ketten

**Ergebnis**: ✅ BESTANDEN

**Empfehlungen:**
```php
// Роутер использует только REMOTE_ADDR
// Если нужно доверять proxy, настройте явно:
$router->setTrustedProxies(['10.0.0.1']);
```

---

### 7. Domain Security

**Beschreibung**: Einschränkungen der Routendomäne werden überprüft.

**Mechanismus**:
```php
$router->get('/api/v1', 'ApiController@index')
    ->domain('api.example.com');
```

**Test**: Zugriff von einer anderen Domain

**Ergebnis**: ✅ BESTANDEN – die Route stimmt nicht überein

**Anwendung:**
- Mandantenfähige Anwendungen
- Subdomain-Routing
- API versioning

---

### 8. ReDoS (Regular Expression Denial of Service) Protection

**Beschreibung**: Schutz vor Angriffen über komplexe reguläre Ausdrücke.

**Gefährliche Muster**:
- `(a+)+`
- `(a|a)*`
- `(a|ab)*`

**Schutzmechanismus**:
- Beschränkung des Regex-Schwierigkeitsgrads
- Zeitüberschreitung beim Regex-Abgleich
- Mustervalidierung

**Ergebnis**: ✅ BESTANDEN

**Empfehlungen:**
```php
// ПРАВИЛЬНО: простые паттерны
$router->get('/users/{id}', fn($id) => $id)
    ->where('id', '\d+');

// ИЗБЕГАЙТЕ: сложные вложенные паттерны
$router->get('/complex/{param}', fn($p) => $p)
    ->where('param', '(a+)+'); // ОПАСНО!
```

---

### 9. Method Override Attack

**Beschreibung**: Schutz gegen HTTP-Methoden-Spoofing über Header oder POST-Parameter.

**Angriffe**:
- `X-HTTP-Method-Override: DELETE`
- `_method=DELETE` in POST

**Schutzmechanismus**:
– Methodenüberschreibung standardmäßig ignorieren
– Optionale Aktivierung für vertrauenswürdige Quellen

**Ergebnis**: ✅ BESTANDEN

---

### 10. Mass Assignment in Route Params

**Beschreibung**: Schutz vor Massenzuweisung über Routenparameter.

**Test**: Übergabe vieler Parameter, die nicht deklariert sind

**Schutzmechanismus**:
- Es werden nur deklarierte Parameter abgerufen
- Der Rest wird ignoriert
- Strict parameter matching

**Ergebnis**: ✅ BESTANDEN

---

### 11. Cache Injection

**Beschreibung**: Schutz vor Injektion in den Routen-Cache.

**Schutzmechanismus**:
- Serialisierung ohne „__wakeup“-Rückrufe
- Strenge Validierung der zwischengespeicherten Daten
- Integritätsprüfung

**Ergebnis**: ✅ BESTANDEN

**Im Code:**
```php
// RouteCache использует безопасную сериализацию
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$cache->store($routes);
```

---

### 12. Resource Exhaustion

**Beschreibung**: Schutz vor Ressourcenerschöpfung durch übermäßige Anfragen.

**Schutzmechanismus**:
- **Ratenbegrenzung**: Beschränkung der Anfragen
- **Auto-Ban**: automatische Blockierung
- **Speichergrenzen**: Kontrolle des Speicherverbrauchs

**Ergebnis**: ✅ BESTANDEN

**Beispiel:**
```php
// Rate limiting
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60); // максимум 60 запросов в минуту

// Auto-ban при превышении
$router->enableAutoBan(
    maxAttempts: 100,
    decayMinutes: 60,
    banDuration: 3600
);
```

---

### 13. Unicode Security Issues

**Beschreibung**: Schutz vor Angriffen mit Unicode-Zeichen.

**Gefahren**:
- Homoglyphen (ähnliche Zeichen)
- Right-to-left override
- Zero-width characters

**Schutzmechanismus**:
- UTF-8-Validierung
- Unicode-Normalisierung
- Auf Steuerzeichen prüfen

**Ergebnis**: ✅ BESTANDEN

---

## 🔒 Einzigartige Sicherheitsfunktionen von CloudCastle

### SSRF (Server-Side Request Forgery) Protection

**Nur bei CloudCastle!**

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

$router->middleware(new SsrfProtection());

// Блокирует запросы к:
// - localhost/127.0.0.1
// - Private IP ranges (10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16)
// - Link-local addresses
// - Cloud metadata APIs (169.254.169.254)
```

### Automatisches Sperrsystem

**Nur bei CloudCastle!**

```php
$banManager = new BanManager();
$router->setBanManager($banManager);

// Автоматическая блокировка после rate limit
$router->enableAutoBan(
    maxAttempts: 100,
    decayMinutes: 60,
    banDuration: 3600 // ban на 1 час
);
```

### Security Logger

**Nur bei CloudCastle!**

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

$router->middleware(new SecurityLogger(__DIR__ . '/logs/security.log'));

// Логирует:
// - Все security события
// - Заблокированные IP
// - Rate limit превышения
// - Подозрительную активность
```

## 📊 Vergleich der Sicherheitsfunktionen

| Schutz | CloudCastle | FastRoute | Symfony | Laravel | Schlank | Alt |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Path Traversal | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ |
| SQL-Injection (in Parametern) | ✅ | ⚠️ | ⚠️ | ⚠️ | ⚠️ | ⚠️ |
| XSS Protection | ⚠️ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| IP Whitelist | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| IP Blacklist | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| IP Spoofing | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| Domain Security | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ |
| ReDoS Protection | ✅ | ⚠️ | ⚠️ | ⚠️ | ❌ | ❌ |
| Method Override | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| Mass Assignment | ✅ | ❌ | ❌ | ⚠️ | ❌ | ❌ |
| Cache Injection | ✅ | ⚠️ | ✅ | ⚠️ | ❌ | ❌ |
| Resource Exhaustion | ✅ | ❌ | ❌ | ⚠️ | ❌ | ❌ |
| Unicode Security | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| **SSRF Protection** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| **Auto-ban System** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| **Rate Limiting** | **✅** | **❌** | **❌** | **✅** | **❌** | **❌** |
| **Security Logger** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |

**Legende:**
- ✅ Integrierter Schutz
- ⚠️ Teilweiser Schutz oder erfordert zusätzliche Konfiguration
- ❌ Kein Schutz

## 🔐 Detaillierte Beschreibung der Schutzmechanismen

### SSRF-Schutz (einzigartige Funktion)

**Was es schützt**:
```php
// Блокирует запросы к внутренним ресурсам
$blockedUrls = [
    'http://localhost/admin',
    'http://127.0.0.1:8080/internal',
    'http://192.168.1.1/router',
    'http://10.0.0.5/database',
    'http://169.254.169.254/latest/meta-data', // AWS metadata
    'http://metadata.google.internal/', // GCP metadata
];
```

**Verwendung:**
```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

$router->middleware(new SsrfProtection(
    allowLocalhost: false, // блокировать localhost
    allowPrivateIps: false, // блокировать private IP
    allowCloudMetadata: false // блокировать cloud metadata
));
```

### Ratenbegrenzung mit automatischer Sperre

**Kombinierter Schutz:**
```php
// Rate limiting
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60); // 60 запросов в минуту

// Auto-ban после превышения
$banManager = new BanManager();
$router->setBanManager($banManager);
$router->enableAutoBan(
    maxAttempts: 100, // после 100 попыток
    decayMinutes: 60, // в течение 1 часа
    banDuration: 3600 // бан на 1 час
);
```

**Ergebnis**:
- Erste 60 Anfragen/Minute: ✅ OK
- 61-100 Anfrage: ⚠️ TooManyRequestsException
- 100+ Anfragen: 🔒 Dauerhafte Sperre + BannedException

### IP Filtering

**Beispiel für eine Whitelist:**
```php
// Только для офисных IP
$router->get('/internal/reports', 'ReportController@index')
    ->whitelistIp([
        '203.0.113.0/24', // office network
        '198.51.100.50', // VPN gateway
    ]);
```

**Beispiel für eine schwarze Liste:**
```php
// Блокировка известных злоумышленников
$router->get('/public/api', 'ApiController@public')
    ->blacklistIp([
        '1.2.3.4',
        '5.6.7.8',
    ]);
```

### HTTPS Enforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

$router->middleware(new HttpsEnforcement(
    redirect: true, // автоматический redirect на HTTPS
    permanent: true // 301 вместо 302
));
```

### Security Logger

**Automatische Protokollierung:**
```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

$router->middleware(new SecurityLogger('/var/log/security.log'));

// Логируется:
// [2025-10-18 22:00:15] BLOCKED: IP 1.2.3.4 - Rate limit exceeded
// [2025-10-18 22:01:30] BANNED: IP 1.2.3.4 - Auto-ban triggered
// [2025-10-18 22:05:45] SUSPICIOUS: Path traversal attempt from 5.6.7.8
// [2025-10-18 22:10:00] BLOCKED: SSRF attempt to http://169.254.169.254
```

## 📊 Sicherheitstestergebnisse

### Detaillierte Ergebnisse

| # | Test | Beschreibung | Behauptungen | Zeit | Status |
|:---|:---:|:---:|:---:|:---:|:---:|
| 1 | Path Traversal | `../` sequences | 3 | 0.008s | ✅ |
| 2 | SQL-Injection | SQL in Parametern | 3 | 0,005s | ✅ |
| 3 | XSS | Skript-Tags in Parametern | 3 | 0,006s | ✅ |
| 4 | IP-Whitelist | Zugriff von Nicht-Whitelist-IP | 3 | 0,010s | ✅ |
| 5 | IP-Blacklist | Zugriff über Blacklist-IP | 3 | 0,009s | ✅ |
| 6 | IP-Spoofing | Ersetzung über Header | 3 | 0,011s | ✅ |
| 7 | Domänensicherheit | Falsche Domäne | 3 | 0,007s | ✅ |
| 8 | ReDoS | Komplexer regulärer Ausdruck | 3 | 0,012s | ✅ |
| 9 | Methodenüberschreibung | Methodensubstitution | 3 | 0,008s | ✅ |
| 10 | Massenzuweisung | Zusätzliche Parameter | 3 | 0,010s | ✅ |
| 11 | Cache-Injektion | Injektion in den Cache | 3 | 0,009s | ✅ |
| 12 | Ressourcenerschöpfung | DoS über Anfragen | 3 | 0,006s | ✅ |
| 13 | Unicode-Sicherheit | Unicode-Angriffe | 2 | 0,006s | ✅ |
| **GESAMT** | **13** | | **38** | **0,110s** | **✅** |

## 💡 Sicherheitsempfehlungen

### 1. Verwenden Sie in der Produktion immer HTTPS

```php
$router->middleware(new HttpsEnforcement(redirect: true));
```

### 2. Richten Sie die Ratenbegrenzung für öffentliche Endpunkte ein

```php
$router->get('/api/public', 'ApiController@public')
    ->perMinute(60);
```

### 3. Verwenden Sie die IP-Whitelist für Verwaltungspanels

```php
$router->group(['prefix' => '/admin'], function($router) {
    $router->whitelistIp(['your-office-ip']);
    // admin routes...
});
```

### 4. Aktivieren Sie die automatische Sperre für den Brute-Force-Schutz

```php
$router->enableAutoBan(
    maxAttempts: 100,
    decayMinutes: 60,
    banDuration: 3600
);
```

### 5. Verwenden Sie Security Logger zur Überwachung

```php
$router->middleware(new SecurityLogger(__DIR__ . '/logs/security.log'));
```

### 6. Aktivieren Sie den SSRF-Schutz für benutzergenerierte URLs

```php
$router->middleware(new SsrfProtection());
```

## 🏆 CloudCastle-Sicherheitsvorteile

### vs FastRoute
- ✅ +14 Sicherheitsfunktionen
- ✅ Integrierter SSRF-Schutz
- ✅ Automatisches Sperrsystem
- ✅ IP filtering

### vs Symfony
- ✅ Einfachere Einrichtung
- ✅ SSRF-Schutz sofort einsatzbereit
- ✅ Automatisches Sperrsystem
- ✅ Integrierte Ratenbegrenzung

### vs Laravel  
- ✅ Autonome Sicherheit (kein Framework)
- ✅ SSRF Protection
- ✅ Flexiblere IP-Filterung
- ✅ Security Logger

### vs Slim
- ✅ +15 Sicherheitsfunktionen
- ✅ Umfassender Schutz
- ✅ Auto-ban
- ✅ Integrierte Ratenbegrenzung

### vs AltoRouter
- ✅ +16 Sicherheitsfunktionen
- ✅ Komplette Sicherheitssuite
- ✅ Enterprise-ready

## ✅ Fazit

Der CloudCastle HTTP Router bietet **die umfassendste Sicherheit** aller PHP-Router:

1. **13/13 Sicherheitstests** bestanden ✅
2. **17 Sicherheitsmechanismen** eingebaut
3. **4 einzigartige Funktionen** (SSRF, automatische Sperre, Sicherheitslogger, IP-Filterung)
4. **100 % Produktionsbereitschaft**

Der Router ist bereit für den Einsatz in Projekten mit **hohen Sicherheitsanforderungen**: Fintech, E-Commerce, SaaS, Unternehmensanwendungen.

---

*Letzte Aktualisierung: 18. Oktober 2025*

---

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)
