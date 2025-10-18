# Leistung

**CloudCastle HTTP Router v1.1.1**  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/performance.md)
- [English](../../en/documentation/performance.md)
- **[Deutsch](performance.md)** (aktuell)
- [Français](../../fr/documentation/performance.md)

---

## ⚡ Hauptmetriken

| Metrik | Wert |
|--------|------|
| **Geschwindigkeit** | 60.095 req/s |
| **Max. Routen** | 740.000+ |
| **Speicher/Route** | 1,47 KB |
| **Suche** | O(1) |
| **Latenz** | 0,02 ms |

---

## 🚀 Optimierung

### 1. Routen-Caching

```php
$router = Router::getInstance();
$router->enableCache('cache/routes.php');
$router->compile();
```

**Effekt**: 50% schnelleres Laden für > 100 Routen

### 2. Indizierung

CloudCastle indiziert Routen automatisch für O(1)-Suche

### 3. Regex-Kompilierung

Regex-Muster werden einmal bei der Registrierung kompiliert

---

## 📊 Benchmarks

### Routen-Registrierung

| Menge | Zeit | Routen/sec |
|-------|------|------------|
| 1.000 | 0,05s | 20.000 |
| 10.000 | 0,50s | 20.000 |
| 100.000 | 4,35s | 22.988 |

### Routen-Suche

| Typ | Zeit |
|-----|------|
| Exakte Übereinstimmung | 0,001ms |
| Mit Parametern | 0,002ms |
| Regex-Muster | 0,005ms |
| Aus Cache | 0,0005ms |

---

**[← Zurück zum Inhaltsverzeichnis](README.md)**

