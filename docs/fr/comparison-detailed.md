[🇷🇺 Русский](ru/comparison-detailed.md) | [🇺🇸 English](en/comparison-detailed.md) | [🇩🇪 Deutsch](de/comparison-detailed.md) | [🇫🇷 Français](fr/comparison-detailed.md) | [🇨🇳 中文](zh/comparison-detailed.md)

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)

---

# Comparaison détaillée avec les routeurs populaires

**Langues :** 🇷🇺 Russe | [🇫🇷 Anglais](../en/comparison-detailed.md) | [🇩🇪 Deutsch](../de/comparison-detailed.md) | [🇫🇷 Français](../fr/comparison-detailed.md) | [🇨🇳中文](../zh/comparison-detailed.md)

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

## 📋 Avis

Ce document contient une comparaison détaillée du routeur HTTP CloudCastle avec les routeurs PHP les plus populaires : FastRoute, Symfony Router, Laravel Router, Slim Router et AltoRouter.

## 🏆 CloudCastle HTTP Router

### Principales fonctionnalités

| Paramètre | Signification |
|:---|:---:|
| **Version** | 1.1.1+ |
| **PHP** | 8.2+ |
| **Performances** | 50 946 requêtes/s en moyenne |
| **Itinéraires maximum** | 1 095 000 |
| **Mémoire/itinéraire** | 1,39 Ko |
| **Installation** | 10 000+ |
| **GitHub Stars** | - |

### ✅ Points forts

1. **Performances exceptionnelles**
   - Fastest

 parmi toutes les solutions testées
   - 50 000+ requêtes/s en conditions réelles
   - Algorithmes de recherche d'itinéraire optimisés

2. **Évolutivité maximale**
   - Prend en charge plus d'un million d'itinéraires
   - Seulement 1,39 Ko de mémoire par route
   - Mise en cache efficace

3. **Sécurité complète**
   - Protection SSRF (fonctionnalité unique)
   - Système d'interdiction automatique
   - IP filtering (whitelist/blacklist)
   - Limitation de débit intégrée
   - Protection contre plus de 13 types d'attaques

4. **Fonctionnalité riche**
   - PSR-15 middleware support
   - Langage d'expression pour les conditions
   - Configuration YAML/XML/JSON/Attributs
   - URL Generation
   - Analytics & Plugins
   - Groupes de routes avec héritage

5. **Code moderne**
   - PHP 8.2+ utilisant de nouvelles fonctionnalités
   - Des types stricts partout
   - PHPStan level max
   - Couverture complète des tests

### ⚠️ Faiblesses

1. **Nouveauté du projet**
   - Moins de soutien communautaire
   - Moins d'exemples prêts à l'emploi
   - Moins célèbre

2. **Exigences PHP**
   - Nécessite PHP 8.2+ (peut être un problème pour les projets existants)

3. **Taille du colis**
   - Plus de fonctionnalités = plus de code
   - Peut être excessif pour des projets simples

### 🎯 Principales fonctionnalités

- ✅ RESTful routing
- ✅ Itinéraires nommés avec génération d'URL
- ✅ Groupes de routes avec préfixes
- ✅ Middleware (global, groupes, routes)
- ✅Compatibilité PSR-15
- ✅ Limitation du débit (par temps/demandes)
- ✅ Système d'interdiction automatique
- ✅ IP whitelist/blacklist
- ✅ SSRF Protection
- ✅ Domain routing
- ✅ Port routing
- ✅ HTTPS enforcement
- ✅ Protocol filtering (HTTP/HTTPS/WS/WSS)
- ✅ YAML configuration
- ✅ XML configuration
- ✅ PHP Attributes (PHP 8)
- ✅ Expression Language
- ✅ Route caching
- ✅ Analytics plugin
- ✅ Logger plugin
- ✅ Response cache plugin
- ✅ Custom plugins
- ✅ Route macros
- ✅ URL matching & generation
- ✅ Route dumper
- ✅ CORS middleware
- ✅ Middleware d'authentification avec rôles

---

## ⚡ FastRoute

### Principales fonctionnalités

| Paramètre | Signification |
|:---|:---:|
| **Version** | 1.3+ |
| **PHP** | 7.2+ |
| **Performances** | 47 033 requêtes/s en moyenne |
| **Itinéraires maximum** | ~500 000 |
| **Mémoire/itinéraire** | 2,1 Ko |
| **Installation** | 50 M+ |
| **GitHub Stars** | 4.9K+ |

### ✅ Points forts

1. **Vitesse**
   - Un des routeurs les plus rapides (après CloudCastle)
   - Algorithme optimisé basé sur des expressions régulières

2. **Simplicité**
   - API minimaliste
   - Facile à intégrer
   - Documentation claire

3. **Popularité**
   - Largement utilisé dans la communauté
   - De nombreux exemples et tutoriels
   - Solution éprouvée

### ⚠️ Faiblesses

1. **Fonctionnalité minimale**
   - Pas de middleware
   - Aucun itinéraire nommé
   - Pas de groupes
   - Routage de base uniquement

2. **Pas de sécurité intégrée**
   - Aucune protection contre les attaques
   - Aucune limitation de taux
   - Pas de filtrage IP

3. **Aucun fichier de configuration**
   - Configuration logicielle uniquement
   - Pas de YAML/XML/JSON

### 🎯 Principales fonctionnalités

- ✅ RESTful routing
- ✅ Route parameters
- ✅ Route caching
- ❌ Named routes
- ❌ Route groups
- ❌ Middleware
- ❌ Rate limiting
- ❌ Security features

### 💡 Quand l'utiliser

- Micro-projets avec des exigences minimales
- Lorsque seul un routage de base est nécessaire
- Projets hérités sur PHP 7.2+

---

## 🎼 Symfony Router

### Principales fonctionnalités

| Paramètre | Signification |
|:---|:---:|
| **Version** | 6.0+ |
| **PHP** | 8.1+ |
| **Performances** | 15 633 requêtes/s en moyenne |
| **Itinéraires maximum** | ~100 000 |
| **Mémoire/itinéraire** | 8,5 Ko |
| **Installation** | 200 millions+ |
| **Étoiles GitHub** | 29K+ (tous Symfony) |

### ✅ Points forts

1. **Enterprise-grade**
   - Solution éprouvée pour les grands projets
   - Fait partie de l'écosystème Symfony
   - Excellente documentation

2. **Fonctionnalité riche**
   - Expression Language
   - Attributes support
   - YAML/XML/JSON configuration
   - URL generation

3. **Maturité du projet**
   - Plus de 15 ans de développement
   - Immense communauté
   - De nombreuses solutions prêtes à l'emploi

### ⚠️ Faiblesses

1. ** Mauvaises performances **
   - 3,2 fois plus lent que CloudCastle
   - Grands frais généraux
   - Besoin de ressources

2. **Difficulté**
   - Courbe d'apprentissage abrupte
   - Beaucoup d'abstractions
   - Peut être redondant

3. **Grande taille**
   - 8,5 Ko de mémoire par itinéraire
   - Beaucoup de dépendances
   - Colis lourd

### 🎯 Principales fonctionnalités

- ✅ RESTful routing
- ✅ Named routes
- ✅ Route groups
- ✅ URL generation
- ✅ YAML/XML/JSON configuration
- ✅ PHP Attributes
- ✅ Expression Language
- ✅ Route caching
- ❌ Middleware (composants séparés requis)
- ❌ Rate limiting
- ❌ Auto-ban
- ❌ SSRF Protection

### 💡 Quand l'utiliser

- Projets d'entreprise sur Symfony
- Lorsque vous avez besoin d'un écosystème complet
- Projets avec des exigences de stabilité

---

## 🔴 Laravel Router

### Principales fonctionnalités

| Paramètre | Signification |
|:---|:---:|
| **Version** | 10.0+ |
| **PHP** | 8.1+ |
| **Performances** | 16 233 requêtes/s en moyenne |
| **Itinéraires maximum** | ~80 000 |
| **Mémoire/itinéraire** | 10,2 Ko |
| **Installation** | 150 millions+ |
| **Étoiles GitHub** | 75K+ (tous Laravel) |

### ✅ Points forts

1. **Intégration Laravel**
   - Seamless integration
   - Eloquent integration
   - Blade templates
   - Autorisation intégrée

2. **Developer Experience**
   -Excellent DX
   - API simple et claire
   - Bonne documentation

3. **Fonctionnalité**
   - Named routes
   - Route groups
   - Middleware
   - Rate limiting

### ⚠️ Faiblesses

1. ** Mauvaises performances **
   - Le plus lent parmi les modernes
   - Beaucoup de frais généraux du framework
   - Besoin de ressources

2. **Dépendance Laravel**
   - Difficile à utiliser en dehors de Laravel
   - Beaucoup de dépendances
   - Colis lourd

3. **Évolutivité**
   - Limiter ~ 80 000 itinéraires
   - 10+ Ko par itinéraire
   - Consommation de mémoire élevée

### 🎯 Principales fonctionnalités

- ✅ RESTful routing
- ✅ Named routes
- ✅ Route groups
- ✅ Middleware
- ✅ Rate limiting
- ✅ URL generation
- ✅ Route caching
- ❌ PSR-15
- ❌ YAML/XML/JSON config
- ❌ Auto-ban
- ❌ SSRF Protection
- ❌ Expression Language

### 💡 Quand l'utiliser

- Projets sur le framework Laravel
- Quand le DX est plus important que la performance
- Petites et moyennes applications

---

## 🍃 Slim Router

### Principales fonctionnalités

| Paramètre | Signification |
|:---|:---:|
| **Version** | 4.0+ |
| **PHP** | 7.4+ |
| **Performances** | 37 167 requêtes/s en moyenne |
| **Itinéraires maximum** | ~200 000 |
| **Mémoire/itinéraire** | 4,8 Ko |
| **Installation** | 20 M+ |
| **GitHub Stars** | 11.7K+ |

### ✅ Points forts

1. **Microframework**
   - Léger
   - Facile à utiliser
   - Démarrage rapide

2. **Compatible PSR**
   - PSR-7 (HTTP messages)
   - PSR-15 (Middleware)
   - PSR-11 (Container)

3. **Bonnes performances**
   - Symfony/Laravel plus rapide
   - Optimisé pour l'API

### ⚠️ Faiblesses

1. **Fonctionnalité limitée**
   - Fonctionnalité de base
   - Il n'y a pas beaucoup de fonctionnalités avancées
   - Pas de sécurité intégrée

2. **Productivité inférieure**
   - 37% plus lent que CloudCastle
   - 27 % plus lent que FastRoute

3. **Évolutivité**
   - Limiter ~ 200 000 itinéraires
   - Consommation moyenne de mémoire

### 🎯 Principales fonctionnalités

- ✅ RESTful routing
- ✅ Named routes
- ✅ Route groups
- ✅ Middleware (PSR-15)
- ✅ URL generation
- ❌ Rate limiting
- ❌ Route caching
- ❌ YAML/XML/JSON config
- ❌ Auto-ban
- ❌ SSRF Protection

### 💡 Quand l'utiliser

- Applications API-first
- Microservices
- Lorsque vous avez besoin d'un PSR-15 sans frais généraux d'entreprise

---

## 🗺️ AltoRouter

### Principales fonctionnalités

| Paramètre | Signification |
|:---|:---:|
| **Version** | 2.0+ |
| **PHP** | 7.2+ |
| **Performances** | 39 967 req/s en moyenne |
| **Itinéraires maximum** | ~150 000 |
| **Mémoire/itinéraire** | 6,1 Ko |
| **Installation** | 5M+ |
| **GitHub Stars** | 1.3K+ |

### ✅ Points forts

1. **Simplicité**
   -API très simple
   - Facile à apprendre
   - Code minimum

2. **Bonnes performances**
   - Laravel/Symfony plus rapide
   - Optimisé

3. **Named routes**
   - Prise en charge des itinéraires nommés
   - URL generation

### ⚠️ Faiblesses

1. **Fonctionnalité limitée**
   - Pas de middleware
   - Pas de groupes
   - Aucun fichier de configuration

2. **Petite communauté**
   - Moins d'exemples
   - Moins de soutien
   - Moins de mises à jour

3. **Aucune fonctionnalité de sécurité**
   - Aucune protection contre les attaques
   - Aucune limitation de taux
   - Pas de filtrage IP

### 🎯 Principales fonctionnalités

- ✅ RESTful routing
- ✅ Named routes
- ✅ URL generation
- ✅ Route matching
- ❌ Route groups
- ❌ Middleware
- ❌ Rate limiting
- ❌ Route caching
- ❌ YAML/XML/JSON config

### 💡 Quand l'utiliser

- Projets simples
- Lorsque vous avez besoin d'un routeur léger
- Projets hérités

---

## 📊 Tableau récapitulatif comparatif

### Performance

| Routeur | Requête/Sec | contre CloudCastle | Évaluation |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **50,946** | **100%** | 🥇 |
| FastRoute | 47,033 | 92.3% | 🥈 |
| AltoRouter | 39,967 | 78.4% | 🥉 |
| Slim | 37,167 | 72.9% | 4 |
| Laravel | 16,233 | 31.9% | 5 |
| Symfony | 15,633 | 30.7% | 6 |

### Fonctionnalité (sur 25 fonctionnalités)

| Routeur | Quantité | Pourcentage | Évaluation |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **25** | **100%** | 🥇 |
| Symfony | 10 | 40% | 🥈 |
| Laravel | 9 | 36% | 🥉 |
| Slim | 7 | 28% | 4 |
| AltoRouter | 4 | 16% | 5 |
| FastRoute | 3 | 12% | 6 |

### Évolutivité

| Routeur | Itinéraires maximum | Mémoire | Évaluation |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **1,095K** | **1.39 KB** | 🥇 |
| FastRoute | 500K | 2.1 KB | 🥈 |
| Slim | 200K | 4.8 KB | 🥉 |
| AltoRouter | 150K | 6.1 KB | 4 |
| Symfony | 100K | 8.5 KB | 5 |
| Laravel | 80K | 10.2 KB | 6 |

### Note globale

| Lieu | Routeur | Prod. | Fonctionnel | Échelle | Total |
|:---|:---:|:---:|:---:|:---:|:---:|
| 🥇 | **CloudCastle** | 10 | 10 | 10 | **30** |
| 🥈 | FastRoute | 9 | 3 | 9 | **21** |
| 🥉 | Slim | 7 | 7 | 7 | **21** |
| 4 | Symfony | 3 | 9 | 5 | **17** |
| 5 | AltoRouter | 8 | 4 | 6 | **18** |
| 6 | Laravel | 4 | 8 | 4 | **16** |

## 🎯 Recommandations pour choisir

### Choisissez le routeur HTTP CloudCastle si :

- ✅ Besoin de performances maximales
- ✅ Évolutivité requise (plus de 1000 itinéraires)
- ✅ La sécurité des applications est importante
- ✅ Besoin de fonctionnalités riches prêtes à l'emploi
- ✅ Vous utilisez PHP 8.2+
- ✅ Construire une application moderne

### Choisissez FastRoute si :

- ✅ Seul un routage de base est nécessaire
- ✅ Le minimalisme et la rapidité sont plus importants que la fonctionnalité
- ✅ Projet hérité en PHP 7.2+
- ✅Micro projet

### Choisissez Symfony Router si :

- ✅ Utilisation du framework Symfony
- ✅ Vous avez besoin d'une plateforme d'entreprise éprouvée
- ✅ Les performances ne sont pas critiques
- ✅ La maturité du projet est importante

### Choisissez Laravel Router si :

- ✅ Construire sur le framework Laravel
- ✅ DX est plus important que la performance
- ✅ Petit/moyen projet

### Choisissez un routeur mince si :

- ✅ Vous avez besoin d'un routeur léger PSR-15
- ✅ Projet API-first
- ✅ Microservices

### Choisissez AltoRouter si :

- ✅ Projet très simple
- ✅ Code minimum requis
- ✅ Prise en charge héritée

---

## 📈Conclusion

**CloudCastle HTTP Router** est le meilleur choix pour les applications PHP modernes, combinant :

1. **Performances maximales** (50 000+ requêtes/sec)
2. **Évolutivité exceptionnelle** (plus de 1 million de routes)
3. **Sécurité complète** (13+ protections)
4. **Fonctionnalité riche** (25 fonctionnalités)
5. **Technologies modernes** (PHP 8.2+, PSR-15)

Le routeur convient aussi bien aux petits projets qu'aux applications d'entreprise, offrant le meilleur équilibre entre performances, fonctionnalités et sécurité du marché.

---

*Dernière mise à jour : 18 octobre 2025*

---

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)
