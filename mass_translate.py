#!/usr/bin/env python3
"""
Массовое создание структуры переводов документации.
Сохраняет код, таблицы, примеры. Переводит заголовки и короткие описания.
"""

import re
from pathlib import Path

# Словари переводов для заголовков и общих терминов
TRANSLATIONS = {
    'en': {
        'Документация': 'Documentation',
        'Оглавление': 'Table of Contents',
        'Главная': 'Home',
        'Обзор': 'Overview',
        'Описание': 'Description',
        'Пример': 'Example',
        'Примеры': 'Examples',
        'Использование': 'Usage',
        'Сравнение': 'Comparison',
        'Преимущества': 'Advantages',
        'Недостатки': 'Disadvantages',
        'Возможности': 'Features',
        'Заключение': 'Conclusion',
        'Рекомендации': 'Recommendations',
        'Когда использовать': 'When to use',
        'Установка': 'Installation',
        'Быстрый старт': 'Quick Start',
        'Начало работы': 'Getting Started',
        'Лучшие практики': 'Best Practices',
        'Последнее обновление': 'Last updated',
    },
    'de': {
        'Документация': 'Dokumentation',
        'Оглавление': 'Inhaltsverzeichnis',
        'Главная': 'Startseite',
        'Обзор': 'Übersicht',
        'Описание': 'Beschreibung',
        'Пример': 'Beispiel',
        'Примеры': 'Beispiele',
        'Использование': 'Verwendung',
        'Сравнение': 'Vergleich',
        'Преимущества': 'Vorteile',
        'Недостатки': 'Nachteile',
        'Возможности': 'Funktionen',
        'Заключение': 'Fazit',
        'Рекомендации': 'Empfehlungen',
        'Когда использовать': 'Wann zu verwenden',
        'Установка': 'Installation',
        'Быстрый старт': 'Schnellstart',
        'Начало работы': 'Erste Schritte',
        'Лучшие практики': 'Best Practices',
        'Последнее обновление': 'Zuletzt aktualisiert',
    },
    'fr': {
        'Документация': 'Documentation',
        'Оглавление': 'Table des matières',
        'Главная': 'Accueil',
        'Обзор': 'Aperçu',
        'Описание': 'Description',
        'Пример': 'Exemple',
        'Примеры': 'Exemples',
        'Использование': 'Utilisation',
        'Сравнение': 'Comparaison',
        'Преимущества': 'Avantages',
        'Недостатки': 'Inconvénients',
        'Возможности': 'Fonctionnalités',
        'Заключение': 'Conclusion',
        'Рекомендации': 'Recommandations',
        'Когда использовать': 'Quand utiliser',
        'Установка': 'Installation',
        'Быстрый старт': 'Démarrage rapide',
        'Начало работы': 'Premiers pas',
        'Лучшие практики': 'Meilleures pratiques',
        'Последнее обновление': 'Dernière mise à jour',
    },
    'zh': {
        'Документация': '文档',
        'Оглавление': '目录',
        'Главная': '首页',
        'Обзор': '概述',
        'Описание': '描述',
        'Пример': '示例',
        'Примеры': '示例',
        'Использование': '使用方法',
        'Сравнение': '比较',
        'Преимущества': '优势',
        'Недостатки': '缺点',
        'Возможности': '功能',
        'Заключение': '结论',
        'Рекомендации': '建议',
        'Когда использовать': '何时使用',
        'Установка': '安装',
        'Быстрый старт': '快速开始',
        'Начало работы': '入门指南',
        'Лучшие практики': '最佳实践',
        'Последнее обновление': '最后更新',
    }
}

LANGUAGE_FLAGS = {
    'ru': '🇷🇺 Русский',
    'en': '🇬🇧 English',
    'de': '🇩🇪 Deutsch',
    'fr': '🇫🇷 Français',
    'zh': '🇨🇳 中文'
}

def translate_text(text, lang):
    """Простой перевод заголовков и ключевых терминов."""
    if lang == 'ru' or not text:
        return text
    
    result = text
    if lang in TRANSLATIONS:
        for ru_term, translated in TRANSLATIONS[lang].items():
            result = result.replace(ru_term, translated)
    
    return result

def create_language_links(current_lang, filename):
    """Создает ссылки на все языковые версии."""
    links = []
    for lang_code, lang_name in LANGUAGE_FLAGS.items():
        if lang_code == current_lang:
            links.append(lang_name)
        else:
            links.append(f"[{lang_name}](../{lang_code}/{filename})")
    
    return "**Languages:** " + " | ".join(links)

def translate_file(source_path, target_path, target_lang):
    """Переводит файл, сохраняя код и таблицы."""
    with open(source_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    lines = content.split('\n')
    translated_lines = []
    in_code_block = False
    in_table = False
    
    for i, line in enumerate(lines):
        # Code blocks - не переводим
        if line.strip().startswith('```'):
            in_code_block = not in_code_block
            translated_lines.append(line)
            continue
        
        if in_code_block:
            translated_lines.append(line)
            continue
        
        # Таблицы - не переводим данные, только заголовки
        if line.strip().startswith('|') and line.strip().endswith('|'):
            if not in_table and i + 1 < len(lines) and lines[i + 1].strip().startswith('|:---'):
                # Это заголовок таблицы - переводим
                in_table = True
                translated_lines.append(translate_text(line, target_lang))
            else:
                # Данные таблицы или разделитель - не переводим
                translated_lines.append(line)
            continue
        else:
            in_table = False
        
        # Заголовки (начинаются с #) - переводим
        if line.strip().startswith('#'):
            translated_lines.append(translate_text(line, target_lang))
            continue
        
        # Списки и обычный текст - переводим базовые термины
        if line.strip().startswith('-') or line.strip().startswith('*') or line.strip().startswith('>'):
            translated_lines.append(translate_text(line, target_lang))
            continue
        
        # Остальное - базовый перевод терминов
        translated_lines.append(translate_text(line, target_lang))
    
    # Заменяем ссылки на русскую версию на текущий язык
    translated_content = '\n'.join(translated_lines)
    translated_content = translated_content.replace('../ru/', f'../{target_lang}/')
    translated_content = translated_content.replace('docs/ru/', f'docs/{target_lang}/')
    
    # Добавляем языковые ссылки в начало (после первого заголовка)
    lines = translated_content.split('\n')
    new_lines = []
    added_links = False
    
    for i, line in enumerate(lines):
        new_lines.append(line)
        if not added_links and line.strip().startswith('#') and i == 0:
            new_lines.append('')
            new_lines.append(create_language_links(target_lang, Path(source_path).name))
            new_lines.append('')
            added_links = True
    
    translated_content = '\n'.join(new_lines)
    
    # Записываем
    with open(target_path, 'w', encoding='utf-8') as f:
        f.write(translated_content)

print("Скрипт готов к использованию, но я буду переводить вручную для лучшего качества...")
