# Politique de sécurité

[English](../en/SECURITY.md) | [Русский](../../SECURITY.md) | [Deutsch](../de/SECURITY.md) | [**Français**](SECURITY.md) | [中文](../zh/SECURITY.md)

---

## Versions supportées

Nous fournissons des mises à jour de sécurité pour les versions suivantes :

| Version | Supportée          |
| ------- | ------------------ |
| 1.1.x   | :white_check_mark: Oui |
| 1.0.x   | :white_check_mark: Oui |
| < 1.0   | :x: Non            |

## Signalement de vulnérabilités

### Comment signaler

Si vous découvrez une vulnérabilité de sécurité dans CloudCastle HTTP Router, veuillez nous la signaler **en toute confidentialité**. Nous prenons tous les problèmes de sécurité au sérieux.

**NE créez PAS d'issues GitHub publiques pour les vulnérabilités de sécurité.**

### Méthodes de contact

1. **Email :** zorinalexey59292@gmail.com
   - Sujet : `[SECURITY] Description du problème`
   - Inclure : version, description de la vulnérabilité, étapes de reproduction

2. **Telegram :** [@CloudCastle85](https://t.me/CloudCastle85)
   - Pour les cas urgents

### Que inclure dans le rapport

Veuillez inclure les informations suivantes dans votre rapport :

- **Description** de la vulnérabilité
- **Étapes pour reproduire** le problème
- **Version** de la bibliothèque
- **Impact potentiel** de la vulnérabilité
- **Correction suggérée** (si applicable)
- **Vos coordonnées** pour les retours

### À quoi s'attendre

1. **Confirmation de réception** - dans les 24 heures
2. **Analyse initiale** - dans les 48 heures
3. **Plan de correction** - dans les 7 jours
4. **Publication du patch** - selon la gravité :
   - Critique : 1-3 jours
   - Élevée : 7-14 jours
   - Moyenne : 14-30 jours
   - Faible : prochaine version

### Processus de divulgation

1. Nous confirmons la réception du rapport
2. Nous vérifions et évaluons la vulnérabilité
3. Nous développons une correction
4. Nous testons la correction
5. Nous publions le patch
6. Nous publions un avis de sécurité
7. Nous remercions le rapporteur (s'il n'y a pas d'objection)

## Protection intégrée

CloudCastle HTTP Router inclut les mesures de sécurité suivantes :

### Protection contre les attaques

✅ **Protection Path Traversal**
- Nettoyage automatique des chemins
- Blocage des séquences dangereuses (../, ./, \\)
- Validation URI

✅ **Protection SQL Injection**
- Échappement des paramètres de route
- Gestion sécurisée des entrées utilisateur

✅ **Protection XSS**
- Encodage des entités HTML
- Échappement des caractères dangereux
- Compatibilité Content Security Policy

✅ **Protection IP Spoofing**
- Validation des en-têtes X-Forwarded-For
- Validation IP réelle
- Protection contre le spoofing

✅ **Protection ReDoS**
- Limitations regex complexes
- Timeouts de correspondance de patterns
- Patterns par défaut sécurisés

✅ **Protection contre les attaques Method Override**
- Gestion contrôlée de X-HTTP-Method-Override
- Activation optionnelle
- Liste blanche des méthodes autorisées

✅ **Protection Cache Injection**
- Validation des chemins de cache
- Sérialisation sécurisée
- Vérifications d'intégrité

✅ **Protection Resource Exhaustion**
- Limitations du nombre de routes
- Limites mémoire
- Algorithmes optimisés

✅ **Sécurité Unicode**
- Gestion correcte des caractères multibytes
- Normalisation Unicode
- Protection contre les exploits Unicode

### Mesures supplémentaires

✅ **Rate Limiting**
```php
$route->throttle(60, 1); // 60 requêtes par minute
```

✅ **Filtrage IP**
```php
$route->whitelistIp(['192.168.1.0/24']);
$route->blacklistIp(['10.0.0.1']);
```

✅ **Système Auto-Ban**
```php
$banManager->enableAutoBan(5); // Ban après 5 tentatives
```

✅ **Enforcement HTTPS**
```php
$route->https(); // Exiger HTTPS
```

✅ **Isolation de domaine**
```php
$router->group(['domain' => 'api.example.com'], function() {
    // Seulement pour api.example.com
});
```

✅ **Isolation de port**
```php
$router->group(['port' => 8080], function() {
    // Seulement sur le port 8080
});
```

## Recommandations d'utilisation sécurisée

### 1. Toujours utiliser HTTPS en production

```php
// Forcer HTTPS pour les routes sensibles
$router->group(['https' => true], function() {
    $router->post('/login', $action);
    $router->post('/register', $action);
});
```

### 2. Restreindre l'accès aux routes administratives

```php
$router->group([
    'prefix' => '/admin',
    'whitelistIp' => ['192.168.1.0/24'],
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
], function() {
    // Panneau admin
});
```

### 3. Utiliser Rate Limiting sur les endpoints publics

```php
// Endpoints API
$router->get('/api/search', $action)->throttle(30, 1);
$router->post('/api/contact', $action)->throttle(5, 60);
```

### 4. Valider toutes les données d'entrée

```php
$router->get('/users/{id}', $action)
    ->where('id', '[0-9]+'); // Seulement des chiffres

$router->get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+'); // Seulement des caractères sûrs
```

### 5. Utiliser middleware pour l'authentification

```php
$router->group(['middleware' => [AuthMiddleware::class]], function() {
    // Routes protégées
});
```

### 6. Mettre à jour régulièrement la bibliothèque

```bash
composer update cloud-castle/http-router
```

### 7. Surveiller les activités suspectes

```php
$router->registerPlugin(new SecurityLoggerPlugin());
```

### 8. Utiliser l'auto-blocage

```php
$banManager = new BanManager();
$banManager->enableAutoBan(5); // Ban après 5 tentatives échouées
$banManager->setAutoBanDuration(3600); // Pour 1 heure
```

## Limitations connues

### Version PHP

- Nécessite PHP 8.2+
- Les anciennes versions PHP ne sont pas supportées et peuvent avoir des vulnérabilités

### Dépendances

- Mettre à jour régulièrement les dépendances PSR
- Surveiller les avis de sécurité

### Configuration serveur

Le routeur n'est pas responsable de :
- Configuration du serveur web (nginx, Apache)
- Paramètres PHP-FPM
- Règles de pare-feu
- Certificats SSL/TLS

Assurez-vous que votre serveur est correctement configuré.

## Checklist de sécurité

Avant le déploiement en production :

- [ ] HTTPS activé
- [ ] Rate Limiting configuré
- [ ] Filtrage IP pour admin
- [ ] Tous les paramètres validés
- [ ] Middleware d'authentification
- [ ] Journalisation activée
- [ ] Surveillance configurée
- [ ] Mises à jour de sécurité appliquées
- [ ] Mots de passe et tokens dans .env
- [ ] Mode debug désactivé
- [ ] Rapport d'erreurs configuré
- [ ] Système de sauvegarde fonctionnel

## Hall of Fame

Nous remercions les chercheurs suivants pour la divulgation responsable de vulnérabilités :

*(Vide pour le moment - vous pouvez être le premier !)*

## Contacts

- **Email sécurité :** zorinalexey59292@gmail.com
- **Telegram :** [@CloudCastle85](https://t.me/CloudCastle85)
- **GitHub :** [github.com/zorinalexey/cloud-casstle-http-router](https://github.com/zorinalexey/cloud-casstle-http-router)

---

**Merci d'aider à sécuriser CloudCastle HTTP Router !**

---

Dernière mise à jour : 2024-12-20
