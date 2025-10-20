# Теги маршрутов

**Категория:** Организация кода  
**Количество методов:** 5  
**Сложность:** ⭐ Начальный уровень

---

## Методы

### 1. tag()

```php
// Один тег
Route::get('/api/users', $action)->tag('api');

// Множественные теги
Route::get('/api/public/posts', $action)->tag(['api', 'public']);
```

### 2. getRoutesByTag()

```php
$apiRoutes = Route::getRoutesByTag('api');

foreach ($apiRoutes as $route) {
    echo $route->getUri() . "\n";
}
```

### 3. hasTag()

```php
if (Route::router()->hasTag('api')) {
    echo "Has API routes";
}
```

### 4. getAllTags()

```php
$allTags = Route::router()->getAllTags();
// ['api', 'public', 'admin', 'protected']
```

### 5. getTags() (на Route)

```php
$route = Route::current();
$tags = $route->getTags();
// ['api', 'public']
```

## Применение

### Организация маршрутов

```php
Route::group(['tags' => 'api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### Фильтрация

```php
// Получить все публичные API
$publicApi = array_filter(
    routes_by_tag('api'),
    fn($r) => in_array('public', $r->getTags())
);
```

### Документация

```php
$apiRoutes = routes_by_tag('api');
foreach ($apiRoutes as $route) {
    echo "API Endpoint: {$route->getUri()}\n";
}
```

---

**Версия:** 1.1.1  
**Статус:** ✅ Стабильная функциональность

