# Кеширование маршрутов

**Категория:** Производительность  
**Количество методов:** 6  
**Сложность:** ⭐⭐ Средний уровень

---

## Описание

Кеширование позволяет компилировать маршруты в оптимизированный формат и загружать их мгновенно, ускоряя инициализацию приложения в десятки раз.

## Методы

### 1. enableCache()

```php
// Включить кеш в директории
$router->enableCache('/var/cache/routes');
Route::enableCache('cache/routes');
```

### 2. compile()

```php
// Компилировать маршруты
$router->compile();

// Принудительная компиляция
$router->compile(force: true);
```

### 3. loadFromCache()

```php
if ($router->loadFromCache()) {
    echo "Loaded from cache";
} else {
    // Регистрируем маршруты
    require 'routes/web.php';
    $router->compile();
}
```

### 4. clearCache()

```php
$router->clearCache();
```

### 5. autoCompile()

```php
$router->autoCompile();
// Автоматически компилирует при изменениях
```

### 6. isCacheLoaded()

```php
if ($router->isCacheLoaded()) {
    echo "Cache loaded";
}
```

## Производительность

**Без кеша:** ~10-50ms инициализация  
**С кешем:** ~0.1-1ms инициализация  
**Ускорение:** 10-50x

---

**Версия:** 1.1.1  
**Статус:** ✅ Production-ready

