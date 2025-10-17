# Политика безопасности

**Язык**: Русский  
**Переводы
**: [English](docs/en/documentation/SECURITY.md) | [Deutsch](docs/de/documentation/SECURITY.md) | [Français](docs/fr/documentation/SECURITY.md)

---

## 🔒 Поддерживаемые версии

| Версия | Поддержка |
|--------|-----------|
| 1.1.x  | ✅ Да      |
| 1.0.x  | ✅ Да      |
| < 1.0  | ❌ Нет     |

## 🐛 Сообщение об уязвимостях

Если вы обнаружили уязвимость безопасности, **НЕ** создавайте публичный Issue.

Вместо этого:

1. **Email**: zorinalexey59292@gmail.com
2. **Telegram**: [@CloudCastle85](https://t.me/CloudCastle85)
3. **GitHub Security**: https://github.com/zorinalexey/cloud-casstle-http-router/security
4. **Тема письма**: [SECURITY] CloudCastle HTTP Router
5. **Описание**:
    - Тип уязвимости
    - Шаги воспроизведения
    - Возможное влияние
    - Предлагаемое исправление (если есть)

Мы ответим в течение **48 часов** и предоставим исправление в течение **7 дней**.

## 🛡️ Меры безопасности

### Встроенная защита

- ✅ **Автоматический бан** - защита от brute-force
- ✅ **Rate limiting** - контроль частоты запросов
- ✅ **IP фильтрация** - белые/черные списки
- ✅ **HTTPS Enforcement** - принудительное использование HTTPS
- ✅ **SSRF Protection** - защита от Server-Side Request Forgery
- ✅ **Security Logging** - логирование безопасности
- ✅ **Input validation** - валидация входных данных

### OWASP Top 10 Compliance

Библиотека соответствует OWASP Top 10 (2021):

- **A02: Cryptographic Failures** - HTTPS enforcement
- **A03: Injection** - параметризованные запросы
- **A05: Security Misconfiguration** - безопасные настройки по умолчанию
- **A07: Authentication Failures** - rate limiting, auto-ban
- **A09: Security Logging Failures** - встроенное логирование
- **A10: Server-Side Request Forgery** - SSRF protection

## 📋 Рекомендации

### Для разработчиков

1. **Используйте HTTPS** в production
2. **Включите rate limiting** для всех эндпоинтов
3. **Настройте автобан** для критичных операций
4. **Используйте IP фильтрацию** для админ-панели
5. **Регулярно обновляйте** библиотеку

### Пример безопасной конфигурации

```php
// Защита от brute-force
Route::post('/login', 'Auth@login')
    ->throttleWithBan(5, 60, 3, 7200)
    ->https();

// Админ-панель
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'admin'],
], function() {
    Route::whitelistIp(['192.168.1.0/24']);
    Route::https();
    Route::throttleStrict();
});

// API с защитой от SSRF
Route::post('/api/webhook', 'Api@webhook')
    ->middleware([SsrfProtection::class])
    ->perMinute(60);
```

## 🔐 Что делаем мы

- ✅ Регулярный аудит кода
- ✅ Автоматическое сканирование зависимостей
- ✅ Быстрое реагирование на уязвимости
- ✅ Прозрачная коммуникация

## 🏆 Благодарности

Благодарим всех, кто ответственно сообщает об уязвимостях!

---

**Безопасность - наш приоритет** 🔒
