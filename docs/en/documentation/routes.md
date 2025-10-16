# Routes

**CloudCastle HTTP Router v1.1.0**  
**Language**: English

**Translations**: [Русский](../../ru/documentation/routes.md) | [Deutsch](../../de/documentation/routes.md) | [Français](../../fr/documentation/routes.md)

---

## 📋 Basics

### Simple Route
```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', function() {
    return 'User list';
});
```

### HTTP Methods
```php
Route::get('/users', 'UserController@index');
Route::post('/users', 'UserController@store');
Route::put('/users/{id}', 'UserController@update');
Route::delete('/users/{id}', 'UserController@destroy');
```

### Route Parameters
```php
Route::get('/user/{id}', function($id) {
    return "User: $id";
});

// With constraints
Route::get('/user/{id}', 'UserController@show')
    ->where('id', '\d+');
```

### Named Routes
```php
Route::get('/profile', 'ProfileController@show')
    ->name('profile');
```

### Security
```php
Route::post('/login', 'Auth@login')
    ->https()
    ->throttleWithBan(5, 60, 3, 7200);
```

---

**Translations**: [Русский](../../ru/documentation/routes.md) | [Deutsch](../../de/documentation/routes.md) | [Français](../../fr/documentation/routes.md)
