# Именованные маршруты

**Категория:** Организация кода  
**Количество методов:** 6  
**Сложность:** ⭐ Начальный уровень

---

## Методы

### 1. name()

```php
Route::get('/users/{id}', $action)->name('users.show');
Route::post('/users', $action)->name('users.store');
```

### 2. getRouteByName()

```php
$route = Route::getRouteByName('users.show');
```

### 3. currentRouteName()

```php
$name = Route::currentRouteName();
// 'users.show'
```

### 4. currentRouteNamed()

```php
if (Route::currentRouteNamed('users.show')) {
    echo "Viewing user";
}
```

### 5. enableAutoNaming()

```php
Route::enableAutoNaming();

Route::get('/users', $action);       // auto: users.get
Route::post('/users', $action);      // auto: users.post
Route::get('/users/{id}', $action);  // auto: users.id.get
```

### 6. getNamedRoutes()

```php
$namedRoutes = Route::getNamedRoutes();
// ['users.show' => Route, 'users.store' => Route, ...]
```

## Автонейминг

Формат: `{uri}.{method}`

Примеры:
- `/users` GET → `users.get`
- `/users` POST → `users.post`
- `/users/{id}` GET → `users.id.get`
- `/api/v1/users` GET → `api.v1.users.get`
- `/` GET → `root.get`
- `/api-v1/user_profile` GET → `api.v1.user.profile.get`

Нормализация:
- `/` → `.`
- `-` → `.`
- `_` → `.`
- `{id}` → `id`

---

**Версия:** 1.1.1  
**Статус:** ✅ Стабильная функциональность

