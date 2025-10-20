# IP Filtering & Auto-Ban - Детальное описание

[English](../../en/features/IP_FILTERING_FEATURES.md) | **Русский** | [Deutsch](../../de/features/IP_FILTERING_FEATURES.md) | [Français](../../fr/features/IP_FILTERING_FEATURES.md) | [中文](../../zh/features/IP_FILTERING_FEATURES.md)

---

## Содержание

- [IP Whit​elist](#ip-whitelist)
- [IP Blacklist](#ip-blacklist)
- [Auto-Ban System](#auto-ban-system)
- [IP Spoofing Protection](#ip-spoofing-protection)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## IP Whitelist

### Описание

Разрешает доступ только указанным IP адресам. Все остальные блокируются.

### Использование

```php
// Один IP
Route::get('/admin', $action)
    ->whitelistIp('192.168.1.100');

// Множественные IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.100', '192.168.1.101', '192.168.1.102']);

// CIDR нотация (подсети)
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.0/24', '10.0.0.0/8']);

// В группе
Route::group(['whitelistIp' => ['192.168.0.0/16']], function() {
    Route::get('/internal-api/users', $action);
    Route::get('/internal-api/stats', $action);
});
```

### Примеры реального использования

**Админ панель:**
```php
Route::group([
    'prefix' => '/admin',
    'whitelistIp' => [
        '192.168.1.0/24',  // Офисная сеть
        '10.0.1.50',       // VPN IP
    ],
    'middleware' => ['auth', 'admin']
], function() {
    Route::get('/dashboard', $action);
    Route::get('/users', $action);
    Route::get('/settings', $action);
});
```

**Внутренний API:**
```php
Route::group([
    'prefix' => '/internal-api',
    'whitelistIp' => ['127.0.0.1', '::1'], // Только localhost
], function() {
    Route::get('/health', $action);
    Route::get('/metrics', $action);
});
```

### Сравнение с аналогами

| Роутер | Встроенный Whitelist | CIDR | API | Оценка |
|--------|---------------------|------|-----|--------|
| **CloudCastle** | ✅ **Да** | ✅ | **->whitelistIp()** | **⭐⭐⭐⭐⭐** |
| Laravel | ⚠️ Middleware | ⚠️ | middleware | ⭐⭐⭐ |
| Symfony | ⚠️ Component | ✅ | Config | ⭐⭐⭐ |
| FastRoute | ❌ Нет | ❌ | - | ⭐ |
| Slim | ❌ Нет | ❌ | - | ⭐ |

**Плюсы CloudCastle:**
- ✅ Встроено в роутер
- ✅ Простой API (один метод)
- ✅ Поддержка CIDR из коробки
- ✅ На уровне маршрута
- ✅ На уровне группы

**Минусы:**
- Нет (для роутера это идеальная реализация)

---

## IP Blacklist

### Описание

Блокирует доступ для указанных IP адресов. Все остальные разрешены.

### Использование

```php
// Заблокировать один IP
Route::get('/api', $action)
    ->blacklistIp('1.2.3.4');

// Заблокировать множественные
Route::get('/api', $action)
    ->blacklistIp(['1.2.3.4', '5.6.7.8', '9.10.11.12']);

// CIDR нотация (целые подсети)
Route::get('/api', $action)
    ->blacklistIp(['1.2.3.0/24', '5.6.0.0/16']);
```

### Комбинация Whitelist и Blacklist

```php
// Разрешить всю подсеть, кроме конкретного IP
Route::group(['whitelistIp' => ['192.168.0.0/16']], function() {
    Route::get('/api', $action)
        ->blacklistIp(['192.168.1.100']); // Кроме этого
});
```

### Сравнение

| Роутер | Blacklist | Комбинация | Оценка |
|--------|-----------|-----------|--------|
| **CloudCastle** | ✅ | ✅ | **⭐⭐⭐⭐⭐** |
| Laravel | ⚠️ | ⚠️ | ⭐⭐⭐ |
| Symfony | ⚠️ | ⚠️ | ⭐⭐⭐ |
| FastRoute | ❌ | ❌ | ⭐ |
| Slim | ❌ | ❌ | ⭐ |

---

## Auto-Ban System

### Описание

Автоматическая блокировка IP после определенного количества неудачных попыток.

### BanManager

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager();

// Включить автобан после 5 попыток
$banManager->enableAutoBan(5);

// Установить длительность бана (секунды)
$banManager->setAutoBanDuration(3600); // 1 час

// Вручную забанить IP
$banManager->ban('1.2.3.4', 3600);

// Проверить бан
if ($banManager->isBanned('1.2.3.4')) {
    throw new BannedException('Your IP is banned');
}

// Разбанить
$banManager->unban('1.2.3.4');

// Получить список забаненных
$banned = $banManager->getBannedIps();

// Очистить все баны
$banManager->clearAll();
```

### Интеграция с роутером

```php
$banManager = new BanManager();
$banManager->enableAutoBan(5);

// В middleware или перед dispatch
$clientIp = $_SERVER['REMOTE_ADDR'];

if ($banManager->isBanned($clientIp)) {
    http_response_code(403);
    die('Your IP is banned');
}

try {
    $route = $router->dispatch($uri, $method, null, $clientIp);
} catch (Exception $e) {
    // Регистрируем неудачную попытку
    $banManager->recordFailedAttempt($clientIp);
    throw $e;
}
```

### Сравнение

| Роутер | Auto-Ban | API | Встроенный | Оценка |
|--------|----------|-----|-----------|--------|
| **CloudCastle** | ✅ **Да** | **BanManager** | ✅ | **⭐⭐⭐⭐⭐** |
| Laravel | ❌ Нет | Требует пакет | ❌ | ⭐⭐ |
| Symfony | ❌ Нет | Требует пакет | ❌ | ⭐⭐ |
| FastRoute | ❌ Нет | - | ❌ | ⭐ |
| Slim | ❌ Нет | - | ❌ | ⭐ |

**УНИКАЛЬНОСТЬ:** CloudCastle - ЕДИНСТВЕННЫЙ роутер с Auto-Ban системой!

**Плюсы:**
- ✅ Автоматическая защита
- ✅ Настраиваемые пороги
- ✅ Временные и постоянные баны
- ✅ Управление банами

**Применение:**
```php
// Защита от брутфорса
Route::post('/login', function() {
    $banManager = app(BanManager::class);
    
    if ($banManager->isBanned($ip)) {
        throw new BannedException();
    }
    
    if (!auth()->attempt($credentials)) {
        $banManager->recordFailedAttempt($ip);
        throw new AuthenticationException();
    }
    
    return $token;
});
```

---

## IP Spoofing Protection

### Описание

Защита от подмены IP адреса через HTTP заголовки.

### Как работает

Проверка заголовков в правильном порядке:
1. `REMOTE_ADDR` (самый надежный)
2. `HTTP_X_FORWARDED_FOR` (если за proxy)
3. `HTTP_X_REAL_IP`
4. `HTTP_CLIENT_IP`

```php
// Внутренняя логика
function getRealIp() {
    // Сначала доверяем REMOTE_ADDR
    if (isset($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    }
    
    // Затем проверяем proxy заголовки
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]); // Первый IP - реальный клиент
    }
    
    return null;
}
```

### Сравнение

| Роутер | Spoofing защита | Оценка |
|--------|-----------------|--------|
| **CloudCastle** | ✅ | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ | ⭐⭐⭐⭐⭐ |
| Symfony | ✅ | ⭐⭐⭐⭐⭐ |
| FastRoute | ❌ | ⭐ |
| Slim | ❌ | ⭐ |

---

## Заключение

**CloudCastle HTTP Router - лидер по IP Security:**

✅ Встроенный Whitelist  
✅ Встроенный Blacklist  
✅ Поддержка CIDR  
✅ Auto-Ban система (уникально!)  
✅ IP Spoofing защита  
✅ Простой API  

**Рекомендация:** Лучшая реализация IP фильтрации среди всех PHP роутеров!

---

[⬆ Наверх](#ip-filtering--auto-ban---детальное-описание) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

---

© 2024 CloudCastle HTTP Router. Все права защищены.
