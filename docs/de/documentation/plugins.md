# Plugin-System

**CloudCastle HTTP Router v1.1.1**  
**Datum**: September 2025  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- **[Русский](../../ru/documentation/plugins.md)**
- **[English](../../en/documentation/plugins.md)**
- **[Deutsch](../../de/documentation/plugins.md)** (aktuell)
- **[Français](../../fr/documentation/plugins.md)**

---

## Inhaltsverzeichnis

1. [Einführung](#einführung)
2. [Grundkonzepte](#grundkonzepte)
3. [Integrierte Plugins](#integrierte-plugins)
4. [Eigene Plugins erstellen](#eigene-plugins-erstellen)
5. [Lebenszyklus](#lebenszyklus)
6. [API-Referenz](#api-referenz)
7. [Verwendungsbeispiele](#verwendungsbeispiele)

---

## Einführung

Das Plugin-System von CloudCastle Router bietet einen leistungsstarken Mechanismus zur Erweiterung der Router-Funktionalität ohne Änderung des Quellcodes. Plugins können Routing-Lifecycle-Ereignisse abfangen und benutzerdefinierte Logik hinzufügen.

### Vorteile

- 🔌 **Erweiterbarkeit** - neue Funktionen ohne Änderung des Kerncodes
- 🎯 **Modularität** - nur benötigte Plugins aktivieren
- 🚀 **Leistung** - Plugins werden nur bei Bedarf ausgeführt
- 🔧 **Flexibilität** - eigene Plugins für spezifische Anforderungen erstellen
- 📊 **Überwachung** - Router-Leistung in Echtzeit verfolgen

---

## Grundkonzepte

### PluginInterface

Alle Plugins implementieren das `PluginInterface`:

```php
interface PluginInterface
{
    public function getName(): string;
    public function getVersion(): string;
    public function boot(Router $router): void;
    public function isEnabled(): bool;
    public function enable(): void;
    public function disable(): void;
    
    // Lifecycle-Hooks
    public function onRouteRegistered(Route $route): void;
    public function beforeDispatch(Route $route, string $uri, string $method): void;
    public function afterDispatch(Route $route, mixed $result): mixed;
    public function onException(\Exception $exception): void;
}
```

### AbstractPlugin

Basisklasse für die Erstellung von Plugins:

```php
use CloudCastle\Http\Router\Plugin\AbstractPlugin;

class MeinPlugin extends AbstractPlugin
{
    public function getName(): string
    {
        return 'mein_plugin';
    }
    
    // Nur benötigte Methoden überschreiben
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        // Ihre Logik hier
    }
}
```

---

## Integrierte Plugins

### LoggerPlugin

Protokollierung von Router-Ereignissen.

**Funktionen:**
- Routen-Registrierungen protokollieren
- Dispatches protokollieren
- Ausnahmen protokollieren
- Anpassbares Protokollformat
- Selektive Ereignisprotokollierung

**Beispiel:**

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;

$logger = new LoggerPlugin('/var/log/router.log');

// Konfiguration
$logger->setLogRouteRegistration(true);   // Routen-Registrierungen protokollieren
$logger->setLogDispatches(true);          // Dispatches protokollieren
$logger->setLogExceptions(true);          // Ausnahmen protokollieren

$router->registerPlugin($logger);
```

---

### AnalyticsPlugin

Sammeln von Router-Statistiken und Metriken.

**Gesammelte Metriken:**
- Anzahl der Dispatches
- Routen-Hits
- HTTP-Methoden-Statistiken
- Routen-Ausführungszeiten
- Ausnahme-Anzahl
- Beliebteste Route
- Meistgenutzte Methode
- Durchschnittliche Ausführungszeit

**Beispiel:**

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
$router->registerPlugin($analytics);

// ... Router verwenden ...

// Statistiken abrufen
$stats = $analytics->getStatistics();

echo "Gesamt Dispatches: {$stats['total_dispatches']}\n";
echo "Beliebteste Route: {$stats['most_popular_route']}\n";
echo "Durchschnittliche Ausführungszeit: {$stats['average_execution_time']}s\n";

// Statistiken zurücksetzen
$analytics->reset();
```

---

### ResponseCachePlugin

Caching von Routen-Antworten.

**Funktionen:**
- Alle Routen oder selektiv cachen
- Konfigurierbare TTL (Time To Live)
- Automatische Bereinigung abgelaufener Einträge
- Cache-Statistiken
- Cache nach Route löschen

**Beispiel:**

```php
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$cache = new ResponseCachePlugin(
    300,      // 5 Minuten TTL
    false     // Nicht alle Routen cachen
);

// Angeben, welche Routen gecacht werden sollen
$cache->setCacheableRoutes(['users.list', 'posts.index', 'api.data']);

$router->registerPlugin($cache);

// Cache löschen
$cache->clearCache();                  // Gesamten Cache löschen
$cache->clearRouteCache($route);       // Spezifische Route löschen

// Statistiken
$stats = $cache->getCacheStats();
echo "Gesamt gecacht: {$stats['total_cached']}\n";
```

---

## Eigene Plugins erstellen

### Minimales Plugin

```php
use CloudCastle\Http\Router\Plugin\AbstractPlugin;
use CloudCastle\Http\Router\Route;

class AnfragenZaehlerPlugin extends AbstractPlugin
{
    private int $count = 0;
    
    public function getName(): string
    {
        return 'anfragen_zaehler';
    }
    
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        $this->count++;
    }
    
    public function getCount(): int
    {
        return $this->count;
    }
}

// Verwendung
$zaehler = new AnfragenZaehlerPlugin();
$router->registerPlugin($zaehler);

// Später
echo "Verarbeitete Anfragen: " . $zaehler->getCount();
```

### Fortgeschrittenes Plugin

```php
class LeistungsMonitorPlugin extends AbstractPlugin
{
    private array $timings = [];
    private array $langsameRouten = [];
    private float $schwellenwert = 0.5; // 500ms
    
    public function getName(): string
    {
        return 'leistungs_monitor';
    }
    
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        $key = $this->getRouteKey($route);
        $this->timings[$key] = microtime(true);
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        $key = $this->getRouteKey($route);
        
        if (isset($this->timings[$key])) {
            $dauer = microtime(true) - $this->timings[$key];
            
            if ($dauer > $this->schwellenwert) {
                $this->langsameRouten[$key] = [
                    'route' => $route->getName() ?? $route->getUri(),
                    'dauer' => $dauer,
                    'zeitstempel' => time(),
                ];
            }
        }
        
        return $result;
    }
    
    public function getLangsameRouten(): array
    {
        return $this->langsameRouten;
    }
}
```

---

## Lebenszyklus

### Reihenfolge der Hook-Ausführung

1. **Routen-Registrierung**
   ```
   Router::get() → Plugin::onRouteRegistered()
   ```

2. **Dispatch**
   ```
   Router::dispatch() → Plugin::beforeDispatch()
   ```

3. **Ausführung**
   ```
   Router::executeRoute() → Middleware → Action
   ```

4. **Nach Ausführung**
   ```
   Action-Ergebnis → Plugin::afterDispatch() → return
   ```

5. **Bei Ausnahme**
   ```
   Exception → Plugin::onException() → throw
   ```

---

## API-Referenz

### Router-Methoden

```php
// Plugin registrieren
$router->registerPlugin(PluginInterface $plugin): self

// Plugin entfernen
$router->unregisterPlugin(string $name): self

// Prüfen ob Plugin existiert
$router->hasPlugin(string $name): bool

// Plugin nach Name abrufen
$router->getPlugin(string $name): ?PluginInterface

// Alle Plugins abrufen
$router->getPlugins(): array
```

### Plugin-Methoden

```php
// Grundlegend
getName(): string                    // Eindeutiger Plugin-Name
getVersion(): string                 // Plugin-Version
boot(Router $router): void          // Initialisierung bei Registrierung
isEnabled(): bool                   // Prüfen ob aktiviert
enable(): void                      // Plugin aktivieren
disable(): void                     // Plugin deaktivieren

// Lifecycle-Hooks
onRouteRegistered(Route $route): void
beforeDispatch(Route $route, string $uri, string $method): void
afterDispatch(Route $route, mixed $result): mixed
onException(\Exception $exception): void
```

---

## Verwendungsbeispiele

### Grundlegende Verwendung

```php
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$router = Router::getInstance();

// Plugins registrieren
$router->registerPlugin(new LoggerPlugin('/tmp/router.log'));
$router->registerPlugin(new AnalyticsPlugin());

// Routen definieren
$router->get('/users', 'UserController@index')->name('users.index');

// Router verwenden
$route = $router->dispatch('/users', 'GET');
$result = $router->executeRoute($route);

// Statistiken abrufen
$analytics = $router->getPlugin('analytics');
$stats = $analytics->getStatistics();
```

### Mehrere Plugins

```php
$router
    ->registerPlugin(new LoggerPlugin('/var/log/router.log'))
    ->registerPlugin(new AnalyticsPlugin())
    ->registerPlugin(new ResponseCachePlugin(300))
    ->registerPlugin(new LeistungsMonitorPlugin());

// Alle Plugins arbeiten parallel
```

---

## Siehe auch

- [API-Referenz](api-reference.md)
- [Middleware](middleware.md)
- [Beispiele](../../../examples/)

---

**Erstellt**: September 2025  
**Zuletzt aktualisiert**: Oktober 2025

