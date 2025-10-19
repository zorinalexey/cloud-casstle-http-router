#!/usr/bin/env python3
"""
Полный перевод документации с сохранением кода и таблиц.
"""

import re
from pathlib import Path

# Комплексный словарь английских переводов
EN_DICT = {
    # Заголовки
    'Автоматическое именование маршрутов': 'Automatic Route Naming',
    'Полное руководство по возможностям': 'Complete Features Guide',
    'Сводка по всем тестам': 'All Tests Summary',
    'Начало работы с': 'Getting Started with',
    'Лучшие практики': 'Best Practices',
    'Детальное сравнение с конкурентами': 'Detailed Comparison with Competitors',
    
    # Секции
    '## 📚 Обзор': '## 📚 Overview',
    '## 🎯 Зачем нужно': '## 🎯 Why',
    '## 💡 Как это работает': '## 💡 How It Works',
    '## ⚡ Преимущества': '## ⚡ Advantages',
    '## 🆚 Сравнение с конкурентами': '## 🆚 Comparison with Competitors',
    '## 📊 Примеры использования': '## 📊 Usage Examples',
    '## ✅ Когда использовать': '## ✅ When to Use',
    '## ❌ Когда НЕ использовать': '## ❌ When NOT to Use',
    '## 🔧 Настройка': '## 🔧 Configuration',
    '## 📖 Заключение': '## 📖 Conclusion',
    '## 💡 Рекомендации': '## 💡 Recommendations',
    '### Описание': '### Description',
    '### Пример': '### Example',
    '### Примеры': '### Examples',
    '### Использование': '### Usage',
    '### Проблема без': '### The Problem Without',
    '### Решение с': '### Solution With',
    '### Алгоритм': '### Algorithm',
    
    # Фразы
    'уникальная фича': 'unique feature',
    'уникальная возможность': 'unique capability',
    'которая автоматически': 'that automatically',
    'генерирует имена для маршрутов': 'generates names for routes',
    'на основе их URI и HTTP метода': 'based on their URI and HTTP method',
    'на основе': 'based on',
    'Экономит время разработки': 'Saves development time',
    'Экономия времени': 'Time savings',
    'Устраняет ошибки': 'Eliminates errors',
    'Улучшает читаемость': 'Improves readability',
    'Без дополнительного кода': 'Without additional code',
    'Автоматически': 'Automatically',
    'Вручную': 'Manually',
    'Каждый маршрут': 'Each route',
    'требует явного указания': 'requires explicit specification',
    'Роутер сам генерирует': 'Router automatically generates',
    'в больших проектах': 'in large projects',
    'Нужно вручную': 'Need to manually',
    'Риск ошибок': 'Risk of errors',
    'Только для': 'Only for',
    'Идеально для': 'Perfect for',
    'Используйте для': 'Use for',
    'Не рекомендуется для': 'Not recommended for',
    
    # Сравнения
    'Поддержка': 'Support',
    'Не поддерживается': 'Not supported',
    'Только вручную': 'Manual only',
    'Частично': 'Partial',
    'Полная поддержка': 'Full support',
    'Ограниченная поддержка': 'Limited support',
    
    # Технические
    'Шаги алгоритма': 'Algorithm Steps',
    'Берёт URI': 'Takes URI',
    'Удаляет параметры': 'Removes parameters',
    'Нормализует путь': 'Normalizes path',
    'Добавляет HTTP метод': 'Adds HTTP method',
    'Генерирует имя': 'Generates name',
    
    # Общее
    'Последнее обновление': 'Last updated',
    'Оглавление': 'Table of Contents',
    'Главная': 'Home',
    'Итого': 'Total',
    'Всего': 'Total',
    'Статус': 'Status',
    'Результат': 'Result',
    'Категория': 'Category',
    'Параметр': 'Parameter',
    'Значение': 'Value',
    'Тест': 'Test',
    'Количество': 'Count',
}

def deep_translate(text):
    """Глубокий перевод текста."""
    result = text
    
    # Сначала длинные фразы
    sorted_dict = sorted(EN_DICT.items(), key=lambda x: len(x[0]), reverse=True)
    
    for ru, en in sorted_dict:
        # Используем word boundaries где возможно
        result = re.sub(r'\b' + re.escape(ru) + r'\b', en, result, flags=re.IGNORECASE)
        # Также просто replace для фраз с пунктуацией
        result = result.replace(ru, en)
    
    return result

def process_file(source_path, target_path):
    """Обрабатывает один файл."""
    with open(source_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    lines = content.split('\n')
    result_lines = []
    in_code = False
    
    for i, line in enumerate(lines):
        # Code blocks
        if line.strip().startswith('```'):
            in_code = not in_code
            result_lines.append(line)
            continue
        
        if in_code:
            result_lines.append(line)
            continue
        
        # Таблицы - переводим только заголовки
        if line.strip().startswith('|') and line.strip().endswith('|'):
            if i + 1 < len(lines) and '|:---' in lines[i + 1]:
                # Заголовок таблицы - переводим
                result_lines.append(deep_translate(line))
            else:
                # Данные - не переводим
                result_lines.append(line)
            continue
        
        # Обычный текст - переводим
        result_lines.append(deep_translate(line))
    
    # Сохраняем
    with open(target_path, 'w', encoding='utf-8') as f:
        f.write('\n'.join(result_lines))

print("Скрипт загружен. Готов к использованию.")
