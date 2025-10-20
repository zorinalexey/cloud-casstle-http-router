# Sicherheitsrichtlinie

[English](../en/SECURITY.md) | [Русский](../../SECURITY.md) | [**Deutsch**](SECURITY.md) | [Français](../fr/SECURITY.md) | [中文](../zh/SECURITY.md)

---

## Unterstützte Versionen

Wir bieten Sicherheitsupdates für die folgenden Versionen:

| Version | Unterstützt          |
| ------- | -------------------- |
| 1.1.x   | :white_check_mark: Ja |
| 1.0.x   | :white_check_mark: Ja |
| < 1.0   | :x: Nein             |

## Meldung von Sicherheitslücken

### Wie melden

Wenn Sie eine Sicherheitslücke in CloudCastle HTTP Router entdecken, melden Sie diese bitte **vertraulich** an uns. Wir nehmen alle Sicherheitsprobleme ernst.

**Erstellen Sie KEINE öffentlichen GitHub Issues für Sicherheitslücken.**

### Kontaktmethoden

1. **E-Mail:** zorinalexey59292@gmail.com
   - Betreff: `[SECURITY] Beschreibung des Problems`
   - Enthalten: Version, Beschreibung der Sicherheitslücke, Reproduktionsschritte

2. **Telegram:** [@CloudCastle85](https://t.me/CloudCastle85)
   - Für dringende Fälle

### Was in den Bericht aufnehmen

Bitte nehmen Sie folgende Informationen in Ihren Bericht auf:

- **Beschreibung** der Sicherheitslücke
- **Schritte zur Reproduktion** des Problems
- **Version** der Bibliothek
- **Mögliche Auswirkungen** der Sicherheitslücke
- **Vorgeschlagene Lösung** (falls vorhanden)
- **Ihre Kontaktdaten** für Feedback

### Was zu erwarten ist

1. **Bestätigung des Eingangs** - innerhalb von 24 Stunden
2. **Erstanalyse** - innerhalb von 48 Stunden
3. **Lösungsplan** - innerhalb von 7 Tagen
4. **Patch-Veröffentlichung** - je nach Schweregrad:
   - Kritisch: 1-3 Tage
   - Hoch: 7-14 Tage
   - Mittel: 14-30 Tage
   - Niedrig: nächste Version

### Offenlegungsprozess

1. Wir bestätigen den Eingang des Berichts
2. Wir überprüfen und bewerten die Sicherheitslücke
3. Wir entwickeln eine Lösung
4. Wir testen die Lösung
5. Wir veröffentlichen den Patch
6. Wir veröffentlichen eine Sicherheitsmitteilung
7. Wir danken dem Reporter (falls er nicht dagegen ist)

## Integrierter Schutz

CloudCastle HTTP Router enthält folgende Sicherheitsmaßnahmen:

### Angriffsschutz

✅ **Path Traversal Schutz**
- Automatische Pfadbereinigung
- Blockierung gefährlicher Sequenzen (../, ./, \\)
- URI-Validierung

✅ **SQL Injection Schutz**
- Route-Parameter-Escaping
- Sichere Benutzereingabe-Behandlung

✅ **XSS Schutz**
- HTML-Entity-Kodierung
- Escaping gefährlicher Zeichen
- Content Security Policy Kompatibilität

✅ **IP Spoofing Schutz**
- X-Forwarded-For Header-Validierung
- Echte IP-Validierung
- Spoofing-Schutz

✅ **ReDoS Schutz**
- Komplexe Regex-Beschränkungen
- Pattern-Matching-Timeout
- Sichere Standard-Patterns

✅ **Method Override Angriffsschutz**
- Kontrollierte X-HTTP-Method-Override-Behandlung
- Optionale Aktivierung
- Whitelist erlaubter Methoden

✅ **Cache Injection Schutz**
- Cache-Pfad-Validierung
- Sichere Serialisierung
- Integritätsprüfungen

✅ **Resource Exhaustion Schutz**
- Route-Anzahl-Beschränkungen
- Speicherlimits
- Optimierte Algorithmen

✅ **Unicode Sicherheit**
- Korrekte Multibyte-Zeichen-Behandlung
- Unicode-Normalisierung
- Unicode-Exploit-Schutz

### Zusätzliche Maßnahmen

✅ **Rate Limiting**
```php
$route->throttle(60, 1); // 60 Anfragen pro Minute
```

✅ **IP-Filterung**
```php
$route->whitelistIp(['192.168.1.0/24']);
$route->blacklistIp(['10.0.0.1']);
```

✅ **Auto-Ban-System**
```php
$banManager->enableAutoBan(5); // Ban nach 5 Versuchen
```

✅ **HTTPS-Erzwingung**
```php
$route->https(); // HTTPS erforderlich
```

✅ **Domain-Isolation**
```php
$router->group(['domain' => 'api.example.com'], function() {
    // Nur für api.example.com
});
```

✅ **Port-Isolation**
```php
$router->group(['port' => 8080], function() {
    // Nur auf Port 8080
});
```

## Sichere Nutzungsempfehlungen

### 1. Immer HTTPS in der Produktion verwenden

```php
// HTTPS für sensible Routen erzwingen
$router->group(['https' => true], function() {
    $router->post('/login', $action);
    $router->post('/register', $action);
});
```

### 2. Zugriff auf administrative Routen einschränken

```php
$router->group([
    'prefix' => '/admin',
    'whitelistIp' => ['192.168.1.0/24'],
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
], function() {
    // Admin-Panel
});
```

### 3. Rate Limiting auf öffentlichen Endpunkten verwenden

```php
// API-Endpunkte
$router->get('/api/search', $action)->throttle(30, 1);
$router->post('/api/contact', $action)->throttle(5, 60);
```

### 4. Alle Eingabedaten validieren

```php
$router->get('/users/{id}', $action)
    ->where('id', '[0-9]+'); // Nur Ziffern

$router->get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+'); // Nur sichere Zeichen
```

### 5. Middleware für Authentifizierung verwenden

```php
$router->group(['middleware' => [AuthMiddleware::class]], function() {
    // Geschützte Routen
});
```

### 6. Bibliothek regelmäßig aktualisieren

```bash
composer update cloud-castle/http-router
```

### 7. Verdächtige Aktivitäten überwachen

```php
$router->registerPlugin(new SecurityLoggerPlugin());
```

### 8. Auto-Blockierung verwenden

```php
$banManager = new BanManager();
$banManager->enableAutoBan(5); // Ban nach 5 fehlgeschlagenen Versuchen
$banManager->setAutoBanDuration(3600); // Für 1 Stunde
```

## Bekannte Einschränkungen

### PHP-Version

- Erfordert PHP 8.2+
- Ältere PHP-Versionen werden nicht unterstützt und können Sicherheitslücken haben

### Abhängigkeiten

- PSR-Abhängigkeiten regelmäßig aktualisieren
- Sicherheitsmitteilungen überwachen

### Server-Konfiguration

Der Router ist nicht verantwortlich für:
- Web-Server-Konfiguration (nginx, Apache)
- PHP-FPM-Einstellungen
- Firewall-Regeln
- SSL/TLS-Zertifikate

Stellen Sie sicher, dass Ihr Server korrekt konfiguriert ist.

## Sicherheits-Checkliste

Vor dem Deployment in die Produktion:

- [ ] HTTPS aktiviert
- [ ] Rate Limiting konfiguriert
- [ ] IP-Filterung für Admin
- [ ] Alle Parameter validiert
- [ ] Authentifizierungs-Middleware
- [ ] Protokollierung aktiviert
- [ ] Überwachung konfiguriert
- [ ] Sicherheitsupdates angewendet
- [ ] Passwörter und Token in .env
- [ ] Debug-Modus deaktiviert
- [ ] Fehlerberichterstattung konfiguriert
- [ ] Backup-System funktioniert

## Hall of Fame

Wir danken folgenden Forschern für die verantwortungsvolle Offenlegung von Sicherheitslücken:

*(Noch leer - Sie können der Erste sein!)*

## Kontakte

- **Sicherheits-E-Mail:** zorinalexey59292@gmail.com
- **Telegram:** [@CloudCastle85](https://t.me/CloudCastle85)
- **GitHub:** [github.com/zorinalexey/cloud-casstle-http-router](https://github.com/zorinalexey/cloud-casstle-http-router)

---

**Vielen Dank für die Hilfe bei der Sicherung von CloudCastle HTTP Router!**

---

Zuletzt aktualisiert: 2024-12-20
