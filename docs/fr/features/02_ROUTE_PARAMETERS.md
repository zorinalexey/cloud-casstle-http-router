# Paramètres routeов

[English](../../en/features/02_ROUTE_PARAMETERS.md) | [Русский](../../ru/features/02_ROUTE_PARAMETERS.md) | [Deutsch](../../de/features/02_ROUTE_PARAMETERS.md) | **Français** | [中文](../../zh/features/02_ROUTE_PARAMETERS.md)

---







---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Catégorie:** Fonctionnalités Principales  
**Nombre de méthodes:** 6  
**Complexité:** ⭐⭐ Intermédiaire уровень

---

## Описание

Paramètres routeов позволяют создавать dynamiques URI с переменными частями, валидировать их и устанавливать valeurs par défaut.

## Fonctionnalités

### 1. Basiques paramètres

**Синтаксис:** `{параметр}`

**Описание:** Определение динамической части URI как paramètreа.

**Exemples:**

```php
// Один параметр
Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

// Dispatch: /users/123 → $id = '123'


// Несколько параметров
Route::get('/posts/{year}/{month}/{slug}', function($year, $month, $slug) {
    return "Post: $year/$month/$slug";
});

// Dispatch: /posts/2024/10/hello-world
// → $year = '2024', $month = '10', $slug = 'hello-world'


// С контроллером
Route::get('/users/{id}/posts/{postId}', [PostController::class, 'show']);
// В контроллере:
// public function show($id, $postId) { ... }


// Получение параметров из объекта Route
Route::get('/api/{version}/users/{id}', function($version, $id) {
    $route = Route::current();
    $params = $route->getParameters();
    // ['version' => 'v1', 'id' => '123']
    
    return "API $version, User $id";
});
```

**Особенности:**
- Paramètres передаются в action по порядку
- Регистр чувствителен
- Могут содержать буквы, цифры, подчеркивания
- Автоматически извлекаются из URI

---

### 2. Contraintes paramètres (where)

**Méthode:** `where(string|array $parameter, ?string $pattern = null): Route`

**Описание:** Добавление регулярных выражений для валидации paramètres.

**Paramètres:**
- `$parameter` - Имя paramètreа или массив [paramètre => паттерн]
- `$pattern` - Регулярное выражение (если $parameter - ligne)

**Exemples:**

```php
// Только цифры
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');
// Совпадет: /users/123, /users/456
// НЕ совпадет: /users/abc, /users/12abc


// Slug (буквы, цифры, дефисы)
Route::get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+');
// Совпадет: /posts/hello-world, /posts/test-123
// НЕ совпадет: /posts/Hello, /posts/test_case


// Email
Route::get('/users/email/{email}', $action)
    ->where('email', '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}');
// Совпадет: /users/email/test@example.com


// UUID
Route::get('/resources/{uuid}', $action)
    ->where('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
// Совпадет: /resources/550e8400-e29b-41d4-a716-446655440000


// Множественные ограничения (массив)
Route::get('/api/{version}/users/{id}', $action)
    ->where([
        'version' => 'v[0-9]+',
        'id' => '[0-9]+'
    ]);
// Совпадет: /api/v1/users/123, /api/v2/users/456
// НЕ совпадет: /api/version1/users/123


// Дата в формате YYYY-MM-DD
Route::get('/posts/{date}', $action)
    ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}');
// Совпадет: /posts/2024-10-20


// Путь к файлу (любые символы)
Route::get('/files/{path}', $action)
    ->where('path', '.+');
// Совпадет: /files/path/to/file.txt, /files/document.pdf
```

**Частые паттерны:**

| Паттерн | Регулярное выражение | Описание |
|---------|---------------------|----------|
| Число | `[0-9]+` | Только цифры |
| Slug | `[a-z0-9-]+` | Буквы, цифры, дефисы |
| UUID | `[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}` | UUID формат |
| Дата | `[0-9]{4}-[0-9]{2}-[0-9]{2}` | YYYY-MM-DD |
| Алфавит | `[a-zA-Z]+` | Только буквы |
| Tout путь | `.+` | Любые символы |

---

### 3. Inline paramètres (paramètres с паттернами в URI)

**Синтаксис:** `{параметр:паттерн}`

**Описание:** Определение паттерна валидации прямо в URI.

**Exemples:**

```php
// Число в URI
Route::get('/users/{id:[0-9]+}', $action);
// Эквивалентно: ->where('id', '[0-9]+')


// Slug
Route::get('/posts/{slug:[a-z0-9-]+}', $action);


// Версия API
Route::get('/api/{version:v[0-9]+}/users', $action);
// Совпадет: /api/v1/users, /api/v2/users


// Комбинация inline и where
Route::get('/api/{version:v[0-9]+}/users/{id}', $action)
    ->where('id', '[0-9]+');


// Сложные паттерны
Route::get('/files/{path:.+}', $action);
// Совпадет: /files/documents/report.pdf


// UUID inline
Route::get('/resources/{uuid:[0-9a-f-]{36}}', $action);


// Дата inline
Route::get('/archive/{date:[0-9]{4}-[0-9]{2}-[0-9]{2}}', $action);
```

**Avantages:**
- Компактный синтаксис
- Паттерн виден сразу в URI
- Меньше кода

**Inconvénients:**
- Менее читаемо для сложных паттернов
- Труднее переиспользовать

---

### 4. Optionnels paramètres

**Синтаксис:** `{параметр?}`

**Описание:** Paramètre необязателен, route совпадет и без него.

**Exemples:**

```php
// Опциональная категория
Route::get('/blog/{category?}', function($category = 'all') {
    return "Category: $category";
});
// Совпадет: /blog → category = 'all'
// Совпадет: /blog/php → category = 'php'


// Пагинация
Route::get('/posts/{page?}', function($page = 1) {
    return "Page: $page";
});
// /posts → page = 1
// /posts/2 → page = 2


// Несколько опциональных
Route::get('/search/{query?}/{limit?}', function($query = '', $limit = 10) {
    return "Search: '$query', Limit: $limit";
});
// /search → query = '', limit = 10
// /search/test → query = 'test', limit = 10
// /search/test/20 → query = 'test', limit = 20


// С ограничениями
Route::get('/api/{version?}', function($version = 'v1') {
    return "API $version";
})
->where('version', 'v[0-9]+');
// /api → version = 'v1'
// /api/v2 → version = 'v2'


// Опциональный с inline паттерном
Route::get('/users/{id:[0-9]+?}', function($id = null) {
    if ($id === null) {
        return 'All users';
    }
    return "User: $id";
});
```

**Important:**
- Optionnels paramètres должны быть в конце URI
- Обязательно указывайте значение par défaut в функции
- Можно комбинировать с `where()` и defaults()

---

### 5. Valeurs par défaut (defaults)

**Méthode:** `defaults(array $defaults): Route`

**Описание:** Installation значений par défaut для paramètres.

**Paramètres:**
- `$defaults` - Массив [paramètre => значение]

**Exemples:**

```php
// Значение по умолчанию для page
Route::get('/posts/{page}', $action)
    ->defaults(['page' => 1]);
// /posts/5 → page = 5
// При обращении без параметра в action придет page = 1


// Множественные значения
Route::get('/search/{query}/{limit}/{offset}', $action)
    ->defaults([
        'query' => '',
        'limit' => 10,
        'offset' => 0
    ]);


// С опциональными параметрами
Route::get('/api/{version?}/users', $action)
    ->defaults(['version' => 'v1']);
// /api/users → version = 'v1'
// /api/v2/users → version = 'v2'


// Для обязательных параметров (fallback)
Route::get('/users/{id}', function($id) {
    // $id всегда будет, но можно установить defaults
    // для дополнительной защиты
    return "User: $id";
})
->where('id', '[0-9]+')
->defaults(['id' => '0']);


// С контроллером
Route::get('/catalog/{category}/{sort}', [CatalogController::class, 'index'])
    ->defaults([
        'category' => 'all',
        'sort' => 'name'
    ]);
```

**Использование:**
- Упрощение обработки опциональных paramètres
- Fallback valeurs
- Конфигурация par défaut

---

### 6. Obtenir paramètres

**Méthodes:**
- `Route::getParameters(): array`
- `Route::getParameter(string $name, mixed $default = null): mixed`

**Описание:** Obtenir значений paramètres из объекта Route.

**Exemples:**

```php
// Получение всех параметров
Route::get('/api/{version}/users/{id}', function($version, $id) {
    $route = Route::current();
    $params = $route->getParameters();
    // [
    //     'version' => 'v1',
    //     'id' => '123'
    // ]
    
    return json_encode($params);
});


// Получение конкретного параметра
Route::get('/posts/{slug}', function($slug) {
    $route = Route::current();
    
    // С default значением
    $slug = $route->getParameter('slug', 'default-post');
    
    // Проверка наличия
    if ($route->hasParameter('slug')) {
        // ...
    }
    
    return "Post: $slug";
});


// Установка параметров программно
Route::get('/custom/{id}', function($id) {
    $route = Route::current();
    
    // Установить дополнительные параметры
    $route->setParameters([
        'id' => $id,
        'user_id' => 123,  // Дополнительный параметр
        'role' => 'admin'
    ]);
    
    $allParams = $route->getParameters();
    return json_encode($allParams);
});


// В middleware
class ParamLoggerMiddleware
{
    public function handle(Route $route, callable $next)
    {
        $params = $route->getParameters();
        error_log('Route params: ' . json_encode($params));
        
        return $next($route);
    }
}
```

---

## Продвинутые паттерны

### Версионирование API

```php
Route::get('/api/{version:v[0-9]+}/users/{id:[0-9]+}', [ApiUserController::class, 'show'])
    ->defaults(['version' => 'v1']);
```

### Локализация

```php
Route::get('/{locale:[a-z]{2}}/posts/{slug}', [PostController::class, 'show'])
    ->defaults(['locale' => 'ru'])
    ->where('slug', '[a-z0-9-]+');
// /ru/posts/hello-world
// /en/posts/hello-world
```

### Дата фильтры

```php
Route::get('/reports/{year:[0-9]{4}}/{month:[0-9]{2}}', [ReportController::class, 'show'])
    ->defaults([
        'year' => date('Y'),
        'month' => date('m')
    ]);
```

### Nested Resources

```php
Route::get('/users/{userId:[0-9]+}/posts/{postId:[0-9]+}/comments/{commentId:[0-9]+}', 
    [CommentController::class, 'show']
);
```

---

## Рекомендации

### ✅ Хорошие практики

1. **Tousгда валидируйте paramètres**
   ```php
   // ✅ Хорошо
   Route::get('/users/{id}', $action)->where('id', '[0-9]+');
   
   // ❌ Плохо
   Route::get('/users/{id}', $action); // Любое значение!
   ```

2. **Используйте говорящие имена**
   ```php
   // ✅ Хорошо
   Route::get('/posts/{slug}', $action);
   
   // ❌ Плохо
   Route::get('/posts/{p}', $action);
   ```

3. **Inline паттерны для простых случаев**
   ```php
   // ✅ Хорошо для простых
   Route::get('/users/{id:[0-9]+}', $action);
   
   // ✅ where() для сложных
   Route::get('/users/{email}', $action)
       ->where('email', '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}');
   ```

4. **Valeurs par défaut для опциональных**
   ```php
   // ✅ Хорошо
   Route::get('/posts/{page?}', function($page = 1) { ... });
   
   // ❌ Плохо
   Route::get('/posts/{page?}', function($page) { ... }); // $page может быть null!
   ```

### ❌ Anti-patterns

1. **Не делайте paramètres слишком partagés**
   ```php
   // ❌ Плохо - ловит всё
   Route::get('/files/{path}', $action);
   
   // ✅ Хорошо
   Route::get('/files/{path:.+}', $action)->where('path', '.*\.(pdf|doc|txt)$');
   ```

2. **Не используйте optionnels paramètres в середине**
   ```php
   // ❌ Плохо - не работает
   Route::get('/posts/{category?}/{slug}', $action);
   
   // ✅ Хорошо
   Route::get('/posts/{slug}/{category?}', $action);
   ```

---

## Performance

| Операция | Время | Remarque |
|----------|-------|-----------|
| Парсинг paramètres | ~1-2μs | Очень быстро |
| Validation where | ~5-10μs | Regex проверка |
| Inline паттерн | ~5-10μs | То же что where |

---

## Sécurité

### ⚠️ Validation обязательна

```php
// ❌ ОПАСНО - SQL Injection
Route::get('/users/{id}', function($id) {
    $user = DB::query("SELECT * FROM users WHERE id = $id"); // Уязвимость!
});

// ✅ БЕЗОПАСНО
Route::get('/users/{id}', function($id) {
    // Валидация паттерном
    return "User: $id";
})
->where('id', '[0-9]+');  // Только цифры!

// ✅ БЕЗОПАСНО - использование prepared statements
Route::get('/users/{id}', function($id) {
    $user = DB::prepare("SELECT * FROM users WHERE id = ?", [$id]);
})
->where('id', '[0-9]+');
```

### Path Traversal Protection

```php
// Роутер автоматически защищает от ../
Route::get('/files/{path}', function($path) {
    // $path никогда не содержит ../../../
    return file_get_contents("storage/$path");
})
->where('path', '[a-zA-Z0-9_/-]+'); // Дополнительная защита
```

---

## Exemples из реальных проектов

### E-commerce

```php
// Категория товаров с пагинацией
Route::get('/products/{category}/{page?}', [ProductController::class, 'index'])
    ->where('category', '[a-z0-9-]+')
    ->where('page', '[0-9]+')
    ->defaults(['page' => 1]);

// Товар по SKU
Route::get('/products/sku/{sku:[A-Z0-9-]+}', [ProductController::class, 'showBySku']);
```

### Блог

```php
// Пост по дате и slug
Route::get('/posts/{year:[0-9]{4}}/{month:[0-9]{2}}/{slug}', [PostController::class, 'show'])
    ->where('slug', '[a-z0-9-]+');

// Архив с опциональным месяцем
Route::get('/archive/{year:[0-9]{4}}/{month:[0-9]{2}?}', [ArchiveController::class, 'show'])
    ->defaults(['month' => '01']);
```

### API

```php
// RESTful с UUID
Route::get('/api/v1/resources/{uuid}', [ApiResourceController::class, 'show'])
    ->where('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

// Версионирование
Route::get('/api/{version:v[0-9]+}/users/{id:[0-9]+}', [ApiUserController::class, 'show'])
    ->defaults(['version' => 'v1']);
```

---

## Voir aussi

- [Базовая маршрутизация](01_BASIC_ROUTING.md)
- [Группы маршрутов](03_ROUTE_GROUPS.md)
- [Безопасность](20_SECURITY.md)
- [Expression Language](13_EXPRESSION_LANGUAGE.md) - для сложных условий

---

**Version:** 1.1.1  
**Дата обновления:** Октябрь 2025  
**Статус:** ✅ Стабильная функциональность


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
