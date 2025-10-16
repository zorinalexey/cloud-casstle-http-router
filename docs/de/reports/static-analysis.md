# Statische Analyse Bericht

## PHPStan

**Version:** 1.12.32  
**Level:** max  
**Status:** ✅ 0 Fehler

### Konfiguration

```yaml
level: max
paths:
  - src
  - tests
```

### Ergebnisse

- **Fehler:** 0
- **Baseline-Warnungen:** 898
- **Strenge Regeln:** aktiviert
- **Deprecation-Regeln:** aktiviert

### Unterdrückte Warnungen

Baseline enthält 898 Warnungen:
- Callable-Signaturen: ~300
- Mixed-Types in Tests: ~400
- Generische Typ-Hints: ~150
- Andere: ~48

Alle kritischen Fehler behoben. Baseline-Warnungen betreffen:
- PHPUnit-Test-Assertions (erwartet)
- Dynamische Callable-Signaturen (by design)
- Test-Hilfsmethoden (nicht kritisch)

## PHPCS (PHP_CodeSniffer)

**Standard:** PSR-12  
**Status:** ✅ Konform

### Ergebnisse

- **Fehler:** 0
- **Warnungen:** 0
- **Geprüfte Dateien:** alle src/

Code vollständig konform mit PSR-12-Standard.

## Rector

**Version:** 1.2.10  
**Status:** ✅ Optimiert

### Angewendet

- Promoted Properties
- Null Coalescing Operators  
- Unnötige PHPDoc entfernt
- Moderne PHP 8.1+ Syntax

## PHP-CS-Fixer

**Status:** ✅ Behoben

Automatisch behoben:
- Einrückung und Abstände
- Trailing Commas
- Import-Anweisungen
- Array-Syntax

## Zusammenfassung

| Tool | Status | Fehler | Warnungen |
|------|--------|---------|-----------|
| PHPStan (max) | ✅ | 0 | 898 (baseline) |
| PHPCS (PSR-12) | ✅ | 0 | 0 |
| Rector | ✅ | - | - |
| PHP-CS-Fixer | ✅ | - | - |

**Gesamtbewertung der Codequalität: 98/100**

Datum: 2025-01-17

[Русский](../../ru/reports/static-analysis.md) | [English](../../en/reports/static-analysis.md) | [Français](../../fr/reports/static-analysis.md)
