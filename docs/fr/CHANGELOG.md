# Changelog

[English](../en/CHANGELOG.md) | [Русский](../../CHANGELOG.md) | [Deutsch](../de/CHANGELOG.md) | [**Français**](CHANGELOG.md) | [中文](../zh/CHANGELOG.md)

---

Tous les changements significatifs du projet sont documentés dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/),
et ce projet adhère à [Semantic Versioning](https://semver.org/lang/fr/).

## [Unreleased]

### Prévu
- Structure Trie pour les routes paramétrées
- Cache Regex compilé
- Optimisations PHP JIT
- Support WebSocket
- Support routage GraphQL

## [1.1.1] - 2024-12-20

### Corrigé
- Ajout du paramètre `protocol` aux méthodes `dispatch` dans la façade et le routeur
- Correction de la chaîne vide après statement dans JsonLoaderTest
- Mise à jour de la configuration Rector pour exclure les avertissements false-positive

### Amélioré
- Compatibilité complète avec PHP 8.4
- Documentation améliorée
- Ajout de rapports de test détaillés

## [1.1.0] - 2024-12-01

### Ajouté
- Expression Language pour les conditions de routage complexes
- Système de plugins pour l'extensibilité
- Auto-naming pour les routes
- Routage basé sur le port
- Système de tags amélioré
- BanManager pour le blocage automatique d'IP
- Enum TimeUnit pour la spécification pratique des intervalles de temps
- Route dumper pour l'export des routes
- UrlMatcher pour la correspondance avancée d'URL

### Modifié
- Système d'indexation optimisé pour la recherche de routes
- Performance Rate Limiter améliorée
- RouteCompiler refactorisé pour de meilleures performances

### Corrigé
- Problèmes avec l'imbrication profonde des groupes
- Fuites mémoire avec un grand nombre de routes
- Fonctionnement incorrect de la whitelist/blacklist IP
  
## [1.0.0] - 2024-11-01

### Ajouté
- Fonctionnalité de base du routeur
- Support de toutes les méthodes HTTP (GET, POST, PUT, PATCH, DELETE, VIEW, ANY, MATCH)
- Système de groupes de routes
- Support middleware
- Routes nommées
- Rate Limiting
- Filtrage IP (whitelist/blacklist)
- Routage par domaine
- Enforcement HTTPS
- Cache des routes
- Générateur d'URL
- Chargeurs de routes multiples :
  - JsonLoader
  - YamlLoader
  - XmlLoader
  - PhpLoader
  - AttributeLoader
- MiddlewareDispatcher
- Paramètres de route avec contraintes
- Compatibilité PSR-7 et PSR-15

### Tests
- 501 tests unitaires
- 13 tests de sécurité
- 5 tests de performance
- Tests de charge
- Tests de stress
- Benchmarks PHPBench

### Documentation
- README.md
- Documentation API détaillée
- Exemples d'utilisation
- Guide utilisateur

## [0.9.0] - 2024-10-15

### Ajouté
- Première version beta
- Routage de base
- Support des paramètres
- Groupes simples

## [0.5.0] - 2024-10-01

### Ajouté
- Version alpha
- Proof of concept
- Tests de base

---

## Types de changements

- **Ajouté** - pour une nouvelle fonctionnalité
- **Modifié** - pour des changements dans une fonctionnalité existante
- **Déprécié** - pour une fonctionnalité qui sera bientôt supprimée
- **Supprimé** - pour une fonctionnalité supprimée
- **Corrigé** - pour les corrections de bugs
- **Sécurité** - pour les corrections de vulnérabilités

---

[Unreleased]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.1.1...HEAD
[1.1.1]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v0.9.0...v1.0.0
[0.9.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v0.5.0...v0.9.0
[0.5.0]: https://github.com/zorinalexey/cloud-casstle-http-router/releases/tag/v0.5.0

