# Руководство по участию в разработке

**Язык**: Русский  
**Переводы**: [English](docs/en/documentation/CONTRIBUTING.md) | [Deutsch](docs/de/documentation/CONTRIBUTING.md) | [Français](docs/fr/documentation/CONTRIBUTING.md)

---

Спасибо за ваш интерес к CloudCastle HTTP Router! Мы рады любому вкладу в проект.

## 🤝 Как можно помочь

- 🐛 Сообщать о багах
- 💡 Предлагать новые возможности
- 📝 Улучшать документацию
- 🔧 Исправлять баги
- ✨ Добавлять новый функционал
- 🧪 Писать тесты

## 📋 Процесс участия

1. **Fork** репозитория
2. **Создайте** ветку для ваших изменений (`git checkout -b feature/amazing-feature`)
3. **Внесите** изменения
4. **Напишите** тесты
5. **Проверьте** что все тесты проходят (`./vendor/bin/phpunit`)
6. **Commit** изменений (`git commit -m 'Add amazing feature'`)
7. **Push** в ветку (`git push origin feature/amazing-feature`)
8. **Создайте** Pull Request

## 🔍 Стандарты кодирования

- **PSR-12** - стандарт кодирования
- **PHPStan** Level 9 - статический анализ
- **PHP 8.2+** - минимальная версия
- **Тесты** - обязательны для нового функционала
- **Документация** - для публичного API

## 🧪 Тестирование

```bash
# Все тесты
./vendor/bin/phpunit

# С покрытием
./vendor/bin/phpunit --coverage-html coverage

# Static analysis
./vendor/bin/phpstan analyse

# Code style
./vendor/bin/phpcs
```

## 📝 Commit сообщения

Используйте понятные сообщения:

- `feat: Add auto-ban system`
- `fix: Fix parameter extraction`
- `docs: Update README`
- `test: Add tests for RateLimiter`
- `refactor: Optimize route matching`

## 🐛 Сообщения об ошибках

Используйте шаблон:

**Описание**: Что не так?  
**Ожидаемое поведение**: Что должно быть?  
**Фактическое поведение**: Что происходит?  
**Шаги воспроизведения**: 1. 2. 3.  
**Версия PHP**: 8.2.x  
**Версия библиотеки**: 1.1.0  

## 💡 Предложения

Открывайте Issue с описанием:
- Какую проблему это решит?
- Как это должно работать?
- Примеры использования

## 📜 Лицензия

Отправляя код, вы соглашаетесь с лицензией MIT.

## 📞 Контакты

Есть вопросы? Свяжитесь с нами:

- **Email**: zorinalexey59292@gmail.com
- **Telegram**: [@CloudCastle85](https://t.me/CloudCastle85)
- **GitHub**: [@zorinalexey](https://github.com/zorinalexey)
- **VK**: [vk.com/leha_zorin](https://vk.com/leha_zorin)

---

**Спасибо за ваш вклад!** 🎉
