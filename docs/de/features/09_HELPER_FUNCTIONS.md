# Hilfsfunktionen

[English](../../en/features/09_HELPER_FUNCTIONS.md) | [Русский](../../ru/features/09_HELPER_FUNCTIONS.md) | [**Deutsch**](09_HELPER_FUNCTIONS.md) | [Français](../../fr/features/09_HELPER_FUNCTIONS.md) | [中文](../../zh/features/09_HELPER_FUNCTIONS.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Kategorie:** Hilfsfunktionen  
**Anzahl Funktionen:** 18  
**Komplexität:** ⭐ Anfänger-Level

---

## Beschreibung

Hilfsfunktionen sind globale PHP-Funktionen, die die Arbeit mit dem Router vereinfachen und eine kurze und bequeme API bieten, ohne vollständige Klassennamen verwenden zu müssen.

## Alle Funktionen

### 1. route()

**Signatur:** `route(?string $name = null, array $parameters = []): ?Route`

**Beschreibung:** Route nach Namen abrufen oder aktuelle Route zurückgeben.

**Parameter:**
- `$name` - Routen-Name (null = aktuelle Route)
- `$parameters` - Parameter für Ersetzung (reserviert)

**Beispiele:**

```php
// Route nach Namen abrufen
$route = route('users.show');

// Aktuelle Route abrufen
$current = route();

// Existenz prüfen
if ($route = route('users.index')) {
    echo "Route existiert: " . $route->getUri();
}

// Routen-Informationen abrufen
$route = route('api.users.show');
if ($route) {
    echo "URI: " . $route->getUri();
    echo "Name: " . $route->getName();
    echo "Methoden: " . implode(', ', $route->getMethods());
}
```

### 2. current_route()

**Signatur:** `current_route(): ?Route`

**Beschreibung:** Aktuell ausgeführte Route abrufen.

**Beispiele:**

```php
$route = current_route();
echo "Aktuell: " . $route->getUri();
```

### 3. previous_route()

**Signatur:** `previous_route(): ?Route`

**Beschreibung:** Zuvor ausgeführte Route abrufen.

**Beispiele:**

```php
$previous = previous_route();
if ($previous) {
    echo "Vorherige: " . $previous->getUri();
}
```

### 4. route_is()

**Signatur:** `route_is(string $name): bool`

**Beschreibung:** Prüfen ob aktuelle Route mit Namen übereinstimmt.

**Beispiele:**

```php
if (route_is('users.show')) {
    echo "Benutzer anzeigen";
}

if (route_is('admin.*')) {
    echo "Admin-Bereich";
}
```

### 5. route_name()

**Signatur:** `route_name(): ?string`

**Beschreibung:** Aktuellen Routen-Namen abrufen.

**Beispiele:**

```php
$name = route_name();
// 'users.show'
```

### 6. router()

**Signatur:** `router(): Router`

**Beschreibung:** Router-Instanz abrufen.

**Beispiele:**

```php
$router = router();
$allRoutes = $router->getAllRoutes();
```

### 7. dispatch_route()

**Signatur:** `dispatch_route(string $uri, string $method = 'GET'): ?Route`

**Beschreibung:** Route dispatchen und ausführen.

**Beispiele:**

```php
$route = dispatch_route('/users/123', 'GET');
```

## Schnellreferenz

```php
// Routen abrufen
route('users.show')          // Nach Namen abrufen
current_route()              // Aktuelle Route
previous_route()             // Vorherige Route

// Routen prüfen
route_is('users.show')       // Namen prüfen
route_name()                 // Namen abrufen

// Router-Zugriff
router()                     // Router abrufen
dispatch_route('/users')     // Route dispatchen
```

## Siehe auch

- [Benannte Routen](07_NAMED_ROUTES.md) - Routen-Benennung
- [URL-Generierung](12_URL_GENERATION.md) - URL-Generierung
- [API-Referenz](../API_REFERENCE.md) - Vollständige API-Referenz

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#hilfsfunktionen)