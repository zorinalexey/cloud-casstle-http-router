# Paramètres de Route

[English](../../en/features/02_ROUTE_PARAMETERS.md) | [Русский](../../ru/features/02_ROUTE_PARAMETERS.md) | [Deutsch](../../de/features/02_ROUTE_PARAMETERS.md) | [**Français**](02_ROUTE_PARAMETERS.md) | [中文](../../zh/features/02_ROUTE_PARAMETERS.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation Détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Catégorie:** Fonctionnalités Principales  
**Nombre de Méthodes:** 6  
**Complexité:** ⭐⭐ Niveau Intermédiaire

---

## Description

Les paramètres de route permettent de créer des URIs dynamiques avec des parties variables, de les valider et de définir des valeurs par défaut.

## Fonctionnalités

### 1. Paramètres de Base

**Syntaxe:** `{parameter}`

**Description:** Définition d'une partie dynamique de l'URI comme paramètre.

**Exemples:**

```php
// Paramètre unique
Route::get('/users/{id}', function($id) {
    return "ID Utilisateur: $id";
});

// Dispatch: /users/123 → $id = '123'


// Plusieurs paramètres
Route::get('/posts/{year}/{month}/{slug}', function($year, $month, $slug) {
    return "Post: $year/$month/$slug";
});

// Dispatch: /posts/2024/10/hello-world
// → $year = '2024', $month = '10', $slug = 'hello-world'


// Avec contrôleur
Route::get('/users/{id}/posts/{postId}', [PostController::class, 'show']);
// Dans le contrôleur:
// public function show($id, $postId) { ... }


// Obtenir les paramètres de l'objet Route
Route::get('/api/{version}/users/{id}', function($version, $id) {
    $route = Route::current();
    $params = $route->getParameters();
    // ['version' => 'v1', 'id' => '123']
    
    return "API $version, Utilisateur $id";
});
```

**Caractéristiques:**
- Les paramètres sont passés à l'action dans l'ordre
- Sensible à la casse
- Peuvent contenir lettres, chiffres, underscores
- Automatiquement extraits de l'URI

---

### 2. Contraintes de Paramètres (where)

**Méthode:** `where(string|array $parameter, ?string $pattern = null): Route`

**Description:** Ajout d'expressions régulières pour la validation des paramètres.

**Paramètres:**
- `$parameter` - Nom du paramètre ou array [parameter => pattern]
- `$pattern` - Expression régulière (si $parameter est string)

**Exemples:**

```php
// Seulement des chiffres
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');

// Seulement des lettres
Route::get('/users/{name}', $action)
    ->where('name', '[a-zA-Z]+');

// Alphanumérique avec tirets
Route::get('/posts/{slug}', $action)
    ->where('slug', '[a-zA-Z0-9-]+');

// Plusieurs contraintes
Route::get('/posts/{year}/{month}', $action)
    ->where([
        'year' => '[0-9]{4}',
        'month' => '0[1-9]|1[0-2]'
    ]);

// Patterns complexes
Route::get('/files/{filename}', $action)
    ->where('filename', '[a-zA-Z0-9._-]+\.(jpg|png|gif)');
```

**Patterns Courants:**
- `[0-9]+` - Seulement des chiffres
- `[a-zA-Z]+` - Seulement des lettres
- `[a-zA-Z0-9]+` - Alphanumérique
- `[a-zA-Z0-9-]+` - Alphanumérique avec tirets
- `[0-9]{4}` - Exactement 4 chiffres
- `[0-9]{1,3}` - 1 à 3 chiffres

---

### 3. Contraintes Inline

**Syntaxe:** `{parameter:pattern}`

**Description:** Définition des contraintes directement dans le pattern URI.

**Exemples:**

```php
// Contrainte inline de chiffres
Route::get('/users/{id:[0-9]+}', function($id) {
    return "ID Utilisateur: $id";
});

// Contrainte inline alphanumérique
Route::get('/posts/{slug:[a-zA-Z0-9-]+}', function($slug) {
    return "Slug Post: $slug";
});

// Plusieurs contraintes inline
Route::get('/posts/{year:[0-9]{4}}/{month:[0-9]{2}}/{slug:[a-zA-Z0-9-]+}', function($year, $month, $slug) {
    return "Post: $year/$month/$slug";
});

// Patterns inline complexes
Route::get('/files/{filename:[a-zA-Z0-9._-]+\.(jpg|png|gif)}', function($filename) {
    return "Fichier: $filename";
});
```

**Avantages:**
- Syntaxe plus concise
- Contraintes visibles dans l'URI
- Meilleure lisibilité
- Matching plus rapide

---

### 4. Paramètres Optionnels

**Syntaxe:** `{parameter?}`

**Description:** Rendre les paramètres optionnels avec des valeurs par défaut.

**Exemples:**

```php
// Paramètre optionnel
Route::get('/users/{id?}', function($id = null) {
    if ($id) {
        return "ID Utilisateur: $id";
    }
    return "Tous les utilisateurs";
});

// Dispatch: /users → $id = null
// Dispatch: /users/123 → $id = '123'


// Optionnel avec valeur par défaut
Route::get('/posts/{page?}', function($page = 1) {
    return "Page: $page";
});

// Dispatch: /posts → $page = 1
// Dispatch: /posts/5 → $page = '5'


// Plusieurs paramètres optionnels
Route::get('/search/{query?}/{page?}', function($query = '', $page = 1) {
    return "Recherche: '$query', Page: $page";
});

// Dispatch: /search → $query = '', $page = 1
// Dispatch: /search/php → $query = 'php', $page = 1
// Dispatch: /search/php/2 → $query = 'php', $page = '2'
```

**Règles:**
- Les paramètres optionnels doivent venir après les obligatoires
- Les valeurs par défaut sont définies dans la signature de l'action
- Peuvent avoir plusieurs paramètres optionnels

---

### 5. Valeurs par Défaut

**Méthode:** `defaults(array $defaults): Route`

**Description:** Définir des valeurs par défaut pour les paramètres.

**Exemples:**

```php
// Valeurs par défaut
Route::get('/posts/{page}', function($page) {
    return "Page: $page";
})->defaults(['page' => 1]);

// Dispatch: /posts → $page = 1
// Dispatch: /posts/5 → $page = '5'


// Plusieurs valeurs par défaut
Route::get('/api/{version}/users/{id}', function($version, $id) {
    return "API $version, Utilisateur $id";
})->defaults(['version' => 'v1', 'id' => 1]);

// Dispatch: /api/users → $version = 'v1', $id = 1
// Dispatch: /api/v2/users/123 → $version = 'v2', $id = '123'


// Avec contraintes et valeurs par défaut
Route::get('/posts/{year}/{month}', function($year, $month) {
    return "Posts pour $year/$month";
})
->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}'])
->defaults(['year' => date('Y'), 'month' => date('m')]);
```

**Cas d'Usage:**
- Versioning d'API
- Valeurs par défaut de pagination
- Valeurs par défaut de date/heure actuelles
- Valeurs par défaut spécifiques à l'utilisateur

---

### 6. Accès aux Paramètres

**Méthodes:**
- `getParameters(): array` - Obtenir tous les paramètres
- `getParameter(string $name): mixed` - Obtenir un paramètre spécifique
- `hasParameter(string $name): bool` - Vérifier si le paramètre existe

**Exemples:**

```php
Route::get('/users/{id}/posts/{postId}', function($id, $postId) {
    $route = Route::current();
    
    // Obtenir tous les paramètres
    $params = $route->getParameters();
    // ['id' => '123', 'postId' => '456']
    
    // Obtenir un paramètre spécifique
    $userId = $route->getParameter('id');
    $postId = $route->getParameter('postId');
    
    // Vérifier si le paramètre existe
    if ($route->hasParameter('id')) {
        return "Utilisateur $userId, Post $postId";
    }
    
    return "Pas d'ID utilisateur";
});
```

**Utilisation Avancée:**

```php
Route::get('/api/{version}/users/{id}/posts/{postId}', function($version, $id, $postId) {
    $route = Route::current();
    $params = $route->getParameters();
    
    // Filtrer les paramètres
    $filteredParams = array_filter($params, function($value, $key) {
        return !empty($value) && $key !== 'version';
    }, ARRAY_FILTER_USE_BOTH);
    
    // Utiliser les paramètres pour la requête de base de données
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

## Meilleures Pratiques

### 1. Nommage des Paramètres

```php
// Bien: Noms descriptifs
Route::get('/users/{userId}', $action);
Route::get('/posts/{postSlug}', $action);
Route::get('/categories/{categoryId}', $action);

// À éviter: Noms génériques
Route::get('/users/{id}', $action);
Route::get('/posts/{slug}', $action);
Route::get('/categories/{id}', $action);
```

### 2. Validation des Contraintes

```php
// Toujours valider les paramètres
Route::get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '[0-9]+');

Route::get('/posts/{slug}', [PostController::class, 'show'])
    ->where('slug', '[a-zA-Z0-9-]+');
```

### 3. Valeurs par Défaut

```php
// Définir des valeurs par défaut sensées
Route::get('/posts/{page}', [PostController::class, 'index'])
    ->defaults(['page' => 1])
    ->where('page', '[0-9]+');
```

### 4. Ordre des Paramètres

```php
// Paramètres obligatoires en premier
Route::get('/users/{id}/posts/{postId}', $action);

// Paramètres optionnels en dernier
Route::get('/search/{query?}/{page?}', $action);
```

---

## Modèles Courants

### 1. Ressources RESTful

```php
Route::get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '[0-9]+');

Route::get('/posts/{slug}', [PostController::class, 'show'])
    ->where('slug', '[a-zA-Z0-9-]+');
```

### 2. Versioning d'API

```php
Route::get('/api/{version}/users/{id}', [ApiController::class, 'show'])
    ->where(['version' => 'v[0-9]+', 'id' => '[0-9]+'])
    ->defaults(['version' => 'v1']);
```

### 3. Pagination

```php
Route::get('/posts/{page}', [PostController::class, 'index'])
    ->where('page', '[0-9]+')
    ->defaults(['page' => 1]);
```

### 4. Téléchargements de Fichiers

```php
Route::get('/files/{filename}', [FileController::class, 'download'])
    ->where('filename', '[a-zA-Z0-9._-]+\.(pdf|doc|docx)');
```

---

## Conseils de Performance

### 1. Contraintes Spécifiques en Premier

```php
// Routes plus spécifiques en premier
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/users/{name:[a-zA-Z]+}', $action);
Route::get('/users/{slug:[a-zA-Z0-9-]+}', $action);
```

### 2. Éviter les Patterns Complexes

```php
// Bien: Patterns simples
Route::get('/posts/{id:[0-9]+}', $action);

// À éviter: Patterns complexes
Route::get('/posts/{id:[0-9]{1,10}}', $action);
```

### 3. Utiliser les Contraintes Inline

```php
// Plus rapide: Contraintes inline
Route::get('/users/{id:[0-9]+}', $action);

// Plus lent: Appels where() séparés
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

---

## Dépannage

### Problèmes Courants

1. **Paramètre non passé**
   - Vérifier le nom du paramètre dans l'URI
   - Vérifier l'ordre des paramètres dans l'action
   - Vérifier les fautes de frappe

2. **Contrainte ne fonctionne pas**
   - Vérifier le pattern regex
   - Vérifier le nom du paramètre
   - Tester le pattern séparément

3. **Problèmes de paramètres optionnels**
   - S'assurer que les paramètres optionnels viennent en dernier
   - Définir les valeurs par défaut dans la signature de l'action
   - Vérifier le pattern URI

### Conseils de Debug

```php
// Activer le mode debug
Route::enableDebug();

// Vérifier le matching des paramètres
$route = Route::match('/users/123', 'GET');
if ($route) {
    $params = $route->getParameters();
    var_dump($params);
}
```

---

## Voir Aussi

- [Routage de Base](01_BASIC_ROUTING.md) - Enregistrement de routes de base
- [Groupes de Routes](03_ROUTE_GROUPS.md) - Organisation des routes
- [Génération d'URL](12_URL_GENERATION.md) - Générer des URLs avec paramètres
- [Langage d'Expression](13_EXPRESSION_LANGUAGE.md) - Validation avancée des paramètres
- [Référence API](../API_REFERENCE.md) - Référence API complète

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#paramètres-de-route)