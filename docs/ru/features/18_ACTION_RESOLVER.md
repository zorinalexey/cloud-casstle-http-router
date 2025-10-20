# Action Resolver

**Категория:** Обработка действий  
**Количество форматов:** 6  
**Сложность:** ⭐⭐ Средний уровень

---

## Поддерживаемые форматы

### 1. Closure (анонимная функция)

```php
Route::get('/users', function() {
    return 'Users list';
});

Route::get('/users/{id}', function($id) {
    return "User: $id";
});
```

### 2. Array [Controller::class, 'method']

```php
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
```

### 3. String "Controller@method"

```php
Route::get('/users', 'UserController@index');
Route::post('/users', 'UserController@store');
```

### 4. String "Controller::method"

```php
Route::get('/users', 'UserController::index');
```

### 5. Invokable Controller

```php
class ShowUserController
{
    public function __invoke(int $id)
    {
        return "User: $id";
    }
}

Route::get('/users/{id}', ShowUserController::class);
```

### 6. Dependency Injection

```php
class UserController
{
    public function __construct(
        private UserRepository $repository
    ) {}
    
    public function index()
    {
        return $this->repository->all();
    }
}

Route::get('/users', [UserController::class, 'index']);
// Зависимости разрешаются автоматически
```

---

**Версия:** 1.1.1  
**Статус:** ✅ Стабильная функциональность

