# Политика безопасности

**Русский** | [English](docs/en/SECURITY.md) | [Deutsch](docs/de/SECURITY.md) | [Français](docs/fr/SECURITY.md) | [中文](docs/zh/SECURITY.md)

---



## Поддерживаемые версии

Мы предоставляем обновления безопасности для следующих версий:

| Версия | Поддерживается          |
| ------ | ----------------------- |
| 1.1.x  | :white_check_mark: Да   |
| 1.0.x  | :white_check_mark: Да   |
| < 1.0  | :x: Нет                 |

## Сообщение об уязвимостях

### Как сообщить

Если вы обнаружили уязвимость безопасности в CloudCastle HTTP Router, пожалуйста, сообщите нам об этом **конфиденциально**. Мы серьезно относимся ко всем вопросам безопасности.

**НЕ создавайте публичные GitHub issues для уязвимостей безопасности.**

### Способы связи

1. **Email:** zorinalexey59292@gmail.com
   - Тема: `[SECURITY] Описание проблемы`
   - Укажите: версию, описание уязвимости, шаги воспроизведения

2. **Telegram:** [@CloudCastle85](https://t.me/CloudCastle85)
   - Для срочных случаев

### Что включить в отчет

Пожалуйста, включите следующую информацию в ваш отчет:

- **Описание** уязвимости
- **Шаги для воспроизведения** проблемы
- **Версия** библиотеки
- **Потенциальное влияние** уязвимости
- **Предложение по исправлению** (если есть)
- **Ваши контакты** для обратной связи

### Что ожидать

1. **Подтверждение получения** - в течение 24 часов
2. **Первичный анализ** - в течение 48 часов
3. **План исправления** - в течение 7 дней
4. **Публикация патча** - в зависимости от серьезности:
   - Критические: 1-3 дня
   - Высокие: 7-14 дней
   - Средние: 14-30 дней
   - Низкие: следующий релиз

### Процесс раскрытия

1. Мы подтверждаем получение отчета
2. Мы проверяем и оцениваем уязвимость
3. Мы разрабатываем исправление
4. Мы тестируем исправление
5. Мы выпускаем патч
6. Мы публикуем security advisory
7. Мы благодарим репортера (если он не против)

## Встроенная защита

CloudCastle HTTP Router включает следующие меры безопасности:

### Защита от атак

✅ **Path Traversal Protection**
- Автоматическая очистка путей
- Блокировка опасных последовательностей (../, ./, \\)
- Валидация URI

✅ **SQL Injection Protection**
- Экранирование параметров маршрутов
- Безопасная обработка пользовательского ввода

✅ **XSS Protection**
- HTML entity encoding
- Экранирование опасных символов
- Content Security Policy совместимость

✅ **IP Spoofing Protection**
- Проверка заголовков X-Forwarded-For
- Валидация реального IP
- Защита от подмены

✅ **ReDoS Protection**
- Ограничения на сложные регулярные выражения
- Таймауты для pattern matching
- Безопасные паттерны по умолчанию

✅ **Method Override Attack Protection**
- Контролируемая обработка X-HTTP-Method-Override
- Опциональная активация
- Белый список разрешенных методов

✅ **Cache Injection Protection**
- Валидация путей кеша
- Безопасная сериализация
- Проверка целостности

✅ **Resource Exhaustion Protection**
- Ограничения на количество маршрутов
- Memory limits
- Оптимизированные алгоритмы

✅ **Unicode Security**
- Правильная обработка мультибайтовых символов
- Нормализация Unicode
- Защита от Unicode exploits

### Дополнительные меры

✅ **Rate Limiting**
```php
$route->throttle(60, 1); // 60 запросов в минуту
```

✅ **IP Filtering**
```php
$route->whitelistIp(['192.168.1.0/24']);
$route->blacklistIp(['10.0.0.1']);
```

✅ **Auto-Ban System**
```php
$banManager->enableAutoBan(5); // Бан после 5 попыток
```

✅ **HTTPS Enforcement**
```php
$route->https(); // Требовать HTTPS
```

✅ **Domain Isolation**
```php
$router->group(['domain' => 'api.example.com'], function() {
    // Только для api.example.com
});
```

✅ **Port Isolation**
```php
$router->group(['port' => 8080], function() {
    // Только на порту 8080
});
```

## Рекомендации по безопасному использованию

### 1. Всегда используйте HTTPS в продакшене

```php
// Принудительное HTTPS для чувствительных маршрутов
$router->group(['https' => true], function() {
    $router->post('/login', $action);
    $router->post('/register', $action);
});
```

### 2. Ограничивайте доступ к административным маршрутам

```php
$router->group([
    'prefix' => '/admin',
    'whitelistIp' => ['192.168.1.0/24'],
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
], function() {
    // Админка
});
```

### 3. Используйте Rate Limiting на публичных endpoints

```php
// API endpoints
$router->get('/api/search', $action)->throttle(30, 1);
$router->post('/api/contact', $action)->throttle(5, 60);
```

### 4. Валидируйте все входные данные

```php
$router->get('/users/{id}', $action)
    ->where('id', '[0-9]+'); // Только цифры

$router->get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+'); // Только безопасные символы
```

### 5. Используйте middleware для аутентификации

```php
$router->group(['middleware' => [AuthMiddleware::class]], function() {
    // Защищенные маршруты
});
```

### 6. Регулярно обновляйте библиотеку

```bash
composer update cloud-castle/http-router
```

### 7. Мониторьте подозрительную активность

```php
$router->registerPlugin(new SecurityLoggerPlugin());
```

### 8. Используйте автоблокировку

```php
$banManager = new BanManager();
$banManager->enableAutoBan(5); // Бан после 5 неудачных попыток
$banManager->setAutoBanDuration(3600); // На 1 час
```

## Известные ограничения

### PHP версия

- Требуется PHP 8.2+
- Старые версии PHP не поддерживаются и могут иметь уязвимости

### Зависимости

- Регулярно обновляйте PSR зависимости
- Следите за security advisories

### Конфигурация сервера

Роутер не отвечает за:
- Конфигурацию веб-сервера (nginx, Apache)
- PHP-FPM настройки
- Firewall правила
- SSL/TLS сертификаты

Убедитесь, что ваш сервер правильно настроен.

## Security Checklist

Перед развертыванием в production:

- [ ] HTTPS включен
- [ ] Rate Limiting настроен
- [ ] IP фильтрация для админки
- [ ] Валидация всех параметров
- [ ] Middleware для аутентификации
- [ ] Логирование включено
- [ ] Мониторинг настроен
- [ ] Обновления безопасности применены
- [ ] Пароли и токены в .env
- [ ] Debug режим выключен
- [ ] Error reporting настроен
- [ ] Backup система работает

## Hall of Fame

Мы благодарим следующих исследователей за ответственное раскрытие уязвимостей:

*(Пока пусто - вы можете стать первым!)*

## Контакты

- **Security Email:** zorinalexey59292@gmail.com
- **Telegram:** [@CloudCastle85](https://t.me/CloudCastle85)
- **GitHub:** [github.com/zorinalexey/cloud-casstle-http-router](https://github.com/zorinalexey/cloud-casstle-http-router)

---

**Благодарим за помощь в обеспечении безопасности CloudCastle HTTP Router!**

---

Последнее обновление: 2024-12-20

