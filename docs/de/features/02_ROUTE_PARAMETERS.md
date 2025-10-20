# Routen-Parameter

[English](../../en/features/02_ROUTE_PARAMETERS.md) | [Русский](../../ru/features/02_ROUTE_PARAMETERS.md) | [**Deutsch**](02_ROUTE_PARAMETERS.md) | [Français](../../fr/features/02_ROUTE_PARAMETERS.md) | [中文](../../zh/features/02_ROUTE_PARAMETERS.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Kategorie:** Kernfunktionen  
**Anzahl Methoden:** 6  
**Komplexität:** ⭐⭐ Mittelstufe

---

## Beschreibung

Routen-Parameter ermöglichen es, dynamische URIs mit variablen Teilen zu erstellen, sie zu validieren und Standardwerte festzulegen.

## Funktionen

### 1. Grundlegende Parameter

**Syntax:** `{parameter}`

**Beschreibung:** Definition eines dynamischen Teils der URI als Parameter.

**Beispiele:**

```php
// Einzelner Parameter
Route::get('/users/{id}', function($id) {
    return "Benutzer-ID: $id";
});

// Dispatch: /users/123 → $id = '123'


// Mehrere Parameter
Route::get('/posts/{year}/{month}/{slug}', function($year, $month, $slug) {
    return "Post: $year/$month/$slug";
});

// Dispatch: /posts/2024/10/hello-world
// → $year = '2024', $month = '10', $slug = 'hello-world'


// Mit Controller
Route::get('/users/{id}/posts/{postId}', [PostController::class, 'show']);
// Im Controller:
// public function show($id, $postId) { ... }


// Parameter aus Route-Objekt abrufen
Route::get('/api/{version}/users/{id}', function($version, $id) {
    $route = Route::current();
    $params = $route->getParameters();
    // ['version' => 'v1', 'id' => '123']
    
    return "API $version, Benutzer $id";
});
```

**Eigenschaften:**
- Parameter werden in Reihenfolge an Action übergeben
- Groß-/Kleinschreibung beachten
- Können Buchstaben, Zahlen, Unterstriche enthalten
- Automatisch aus URI extrahiert

---

### 2. Parameter-Einschränkungen (where)

**Methode:** `where(string|array $parameter, ?string $pattern = null): Route`

**Beschreibung:** Hinzufügen von regulären Ausdrücken zur Parameter-Validierung.

**Parameter:**
- `$parameter` - Parametername oder Array [parameter => pattern]
- `$pattern` - Regulärer Ausdruck (wenn $parameter String ist)

**Beispiele:**

```php
// Nur Ziffern
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');

// Nur Buchstaben
Route::get('/users/{name}', $action)
    ->where('name', '[a-zA-Z]+');

// Alphanumerisch mit Bindestrichen
Route::get('/posts/{slug}', $action)
    ->where('slug', '[a-zA-Z0-9-]+');

// Mehrere Einschränkungen
Route::get('/posts/{year}/{month}', $action)
    ->where([
        'year' => '[0-9]{4}',
        'month' => '0[1-9]|1[0-2]'
    ]);

// Komplexe Muster
Route::get('/files/{filename}', $action)
    ->where('filename', '[a-zA-Z0-9._-]+\.(jpg|png|gif)');
```

**Häufige Muster:**
- `[0-9]+` - Nur Ziffern
- `[a-zA-Z]+` - Nur Buchstaben
- `[a-zA-Z0-9]+` - Alphanumerisch
- `[a-zA-Z0-9-]+` - Alphanumerisch mit Bindestrichen
- `[0-9]{4}` - Genau 4 Ziffern
- `[0-9]{1,3}` - 1 bis 3 Ziffern

---

### 3. Inline-Einschränkungen

**Syntax:** `{parameter:pattern}`

**Beschreibung:** Definition von Einschränkungen direkt im URI-Muster.

**Beispiele:**

```php
// Inline Ziffern-Einschränkung
Route::get('/users/{id:[0-9]+}', function($id) {
    return "Benutzer-ID: $id";
});

// Inline alphanumerische Einschränkung
Route::get('/posts/{slug:[a-zA-Z0-9-]+}', function($slug) {
    return "Post-Slug: $slug";
});

// Mehrere Inline-Einschränkungen
Route::get('/posts/{year:[0-9]{4}}/{month:[0-9]{2}}/{slug:[a-zA-Z0-9-]+}', function($year, $month, $slug) {
    return "Post: $year/$month/$slug";
});

// Komplexe Inline-Muster
Route::get('/files/{filename:[a-zA-Z0-9._-]+\.(jpg|png|gif)}', function($filename) {
    return "Datei: $filename";
});
```

**Vorteile:**
- Prägnantere Syntax
- Einschränkungen in URI sichtbar
- Bessere Lesbarkeit
- Schnelleres Matching

---

### 4. Optionale Parameter

**Syntax:** `{parameter?}`

**Beschreibung:** Parameter optional machen mit Standardwerten.

**Beispiele:**

```php
// Optionaler Parameter
Route::get('/users/{id?}', function($id = null) {
    if ($id) {
        return "Benutzer-ID: $id";
    }
    return "Alle Benutzer";
});

// Dispatch: /users → $id = null
// Dispatch: /users/123 → $id = '123'


// Optional mit Standardwert
Route::get('/posts/{page?}', function($page = 1) {
    return "Seite: $page";
});

// Dispatch: /posts → $page = 1
// Dispatch: /posts/5 → $page = '5'


// Mehrere optionale Parameter
Route::get('/search/{query?}/{page?}', function($query = '', $page = 1) {
    return "Suche: '$query', Seite: $page";
});

// Dispatch: /search → $query = '', $page = 1
// Dispatch: /search/php → $query = 'php', $page = 1
// Dispatch: /search/php/2 → $query = 'php', $page = '2'
```

**Regeln:**
- Optionale Parameter müssen nach erforderlichen kommen
- Standardwerte werden in Action-Signatur gesetzt
- Können mehrere optionale Parameter haben

---

### 5. Standardwerte

**Methode:** `defaults(array $defaults): Route`

**Beschreibung:** Festlegen von Standardwerten für Parameter.

**Beispiele:**

```php
// Standardwerte
Route::get('/posts/{page}', function($page) {
    return "Seite: $page";
})->defaults(['page' => 1]);

// Dispatch: /posts → $page = 1
// Dispatch: /posts/5 → $page = '5'


// Mehrere Standardwerte
Route::get('/api/{version}/users/{id}', function($version, $id) {
    return "API $version, Benutzer $id";
})->defaults(['version' => 'v1', 'id' => 1]);

// Dispatch: /api/users → $version = 'v1', $id = 1
// Dispatch: /api/v2/users/123 → $version = 'v2', $id = '123'


// Mit Einschränkungen und Standardwerten
Route::get('/posts/{year}/{month}', function($year, $month) {
    return "Posts für $year/$month";
})
->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}'])
->defaults(['year' => date('Y'), 'month' => date('m')]);
```

**Anwendungsfälle:**
- API-Versionierung
- Paginierungs-Standardwerte
- Aktuelle Datum/Zeit-Standardwerte
- Benutzer-spezifische Standardwerte

---

### 6. Parameter-Zugriff

**Methoden:**
- `getParameters(): array` - Alle Parameter abrufen
- `getParameter(string $name): mixed` - Spezifischen Parameter abrufen
- `hasParameter(string $name): bool` - Prüfen ob Parameter existiert

**Beispiele:**

```php
Route::get('/users/{id}/posts/{postId}', function($id, $postId) {
    $route = Route::current();
    
    // Alle Parameter abrufen
    $params = $route->getParameters();
    // ['id' => '123', 'postId' => '456']
    
    // Spezifischen Parameter abrufen
    $userId = $route->getParameter('id');
    $postId = $route->getParameter('postId');
    
    // Prüfen ob Parameter existiert
    if ($route->hasParameter('id')) {
        return "Benutzer $userId, Post $postId";
    }
    
    return "Keine Benutzer-ID";
});
```

**Erweiterte Verwendung:**

```php
Route::get('/api/{version}/users/{id}/posts/{postId}', function($version, $id, $postId) {
    $route = Route::current();
    $params = $route->getParameters();
    
    // Parameter filtern
    $filteredParams = array_filter($params, function($value, $key) {
        return !empty($value) && $key !== 'version';
    }, ARRAY_FILTER_USE_BOTH);
    
    // Parameter für Datenbankabfrage verwenden
    $user = User::find($params['id']);
    $post = Post::where('user_id', $params['id'])
                ->where('id', $params['postId'])
                ->first();
    
    return response()->json([
        'user' => $user,
        'post' => $post,
        'api_version' => $params['version']
    ]);
});
```

---

## Best Practices

### 1. Parameter-Benennung

```php
// Gut: Beschreibende Namen
Route::get('/users/{userId}', $action);
Route::get('/posts/{postSlug}', $action);
Route::get('/categories/{categoryId}', $action);

// Vermeiden: Generische Namen
Route::get('/users/{id}', $action);
Route::get('/posts/{slug}', $action);
Route::get('/categories/{id}', $action);
```

### 2. Einschränkungs-Validierung

```php
// Immer Parameter validieren
Route::get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '[0-9]+');

Route::get('/posts/{slug}', [PostController::class, 'show'])
    ->where('slug', '[a-zA-Z0-9-]+');
```

### 3. Standardwerte

```php
// Sinnvolle Standardwerte setzen
Route::get('/posts/{page}', [PostController::class, 'index'])
    ->defaults(['page' => 1])
    ->where('page', '[0-9]+');
```

### 4. Parameter-Reihenfolge

```php
// Erforderliche Parameter zuerst
Route::get('/users/{id}/posts/{postId}', $action);

// Optionale Parameter zuletzt
Route::get('/search/{query?}/{page?}', $action);
```

---

## Häufige Muster

### 1. RESTful-Ressourcen

```php
Route::get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '[0-9]+');

Route::get('/posts/{slug}', [PostController::class, 'show'])
    ->where('slug', '[a-zA-Z0-9-]+');
```

### 2. API-Versionierung

```php
Route::get('/api/{version}/users/{id}', [ApiController::class, 'show'])
    ->where(['version' => 'v[0-9]+', 'id' => '[0-9]+'])
    ->defaults(['version' => 'v1']);
```

### 3. Paginierung

```php
Route::get('/posts/{page}', [PostController::class, 'index'])
    ->where('page', '[0-9]+')
    ->defaults(['page' => 1]);
```

### 4. Datei-Downloads

```php
Route::get('/files/{filename}', [FileController::class, 'download'])
    ->where('filename', '[a-zA-Z0-9._-]+\.(pdf|doc|docx)');
```

---

## Performance-Tipps

### 1. Spezifische Einschränkungen zuerst

```php
// Spezifischere Routen zuerst
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/users/{name:[a-zA-Z]+}', $action);
Route::get('/users/{slug:[a-zA-Z0-9-]+}', $action);
```

### 2. Komplexe Muster vermeiden

```php
// Gut: Einfache Muster
Route::get('/posts/{id:[0-9]+}', $action);

// Vermeiden: Komplexe Muster
Route::get('/posts/{id:[0-9]{1,10}}', $action);
```

### 3. Inline-Einschränkungen verwenden

```php
// Schneller: Inline-Einschränkungen
Route::get('/users/{id:[0-9]+}', $action);

// Langsamer: Separate where()-Aufrufe
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

---

## Fehlerbehebung

### Häufige Probleme

1. **Parameter nicht übergeben**
   - Parametername in URI prüfen
   - Parameter-Reihenfolge in Action verifizieren
   - Tippfehler prüfen

2. **Einschränkung funktioniert nicht**
   - Regex-Muster verifizieren
   - Parametername prüfen
   - Muster separat testen

3. **Optionale Parameter-Probleme**
   - Sicherstellen dass optionale Parameter zuletzt kommen
   - Standardwerte in Action-Signatur setzen
   - URI-Muster prüfen

### Debug-Tipps

```php
// Debug-Modus aktivieren
Route::enableDebug();

// Parameter-Matching prüfen
$route = Route::match('/users/123', 'GET');
if ($route) {
    $params = $route->getParameters();
    var_dump($params);
}
```

---

## Siehe auch

- [Grundlegendes Routing](01_BASIC_ROUTING.md) - Grundlegende Routen-Registrierung
- [Routen-Gruppen](03_ROUTE_GROUPS.md) - Routen-Organisation
- [URL-Generierung](12_URL_GENERATION.md) - URLs mit Parametern generieren
- [Ausdruckssprache](13_EXPRESSION_LANGUAGE.md) - Erweiterte Parameter-Validierung
- [API-Referenz](../API_REFERENCE.md) - Vollständige API-Referenz

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#routen-parameter)