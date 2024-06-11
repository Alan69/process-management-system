# Process Management System

Система управления процессами позволяет создавать и управлять процессами с различными типами полей данных. Проект демонстрирует использование ООП и взаимодействие с базой данных через PDO.

## Функциональные возможности

- Создание процесса с набором полей данных
- Поддержка текстовых, числовых и датированных полей
- Уникальные имена полей
- Значения по умолчанию для полей
- Хранение форматов для числовых и датированных полей
- Сохранение данных в базе данных
- Получение данных из базы с постраничной навигацией
- Возможность добавления новых типов полей

## Структура базы данных

### Таблица `processes`
Хранит информацию о процессах.

| Поле   | Тип     | Описание                 |
|--------|---------|--------------------------|
| id     | INT     | Первичный ключ, автоинкремент |
| name   | VARCHAR | Уникальное имя процесса   |

### Таблица `fields`
Хранит информацию о возможных полях.

| Поле          | Тип    | Описание                          |
|---------------|--------|-----------------------------------|
| id            | INT    | Первичный ключ, автоинкремент     |
| name          | VARCHAR| Уникальное имя поля               |
| type          | ENUM   | Тип поля (text, number, date)     |
| default_value | VARCHAR| Значение по умолчанию             |
| format        | VARCHAR| Формат для числовых и датированных полей |

### Таблица `process_fields`
Связующая таблица между процессами и их полями.

| Поле       | Тип | Описание                          |
|------------|-----|-----------------------------------|
| process_id | INT | Идентификатор процесса            |
| field_id   | INT | Идентификатор поля                |
| value      | VARCHAR | Значение поля                  |

## Установка

1. Клонируйте репозиторий:
    ```sh
    git clone https://github.com/Alan69/process-management-system.git
    ```

2. Перейдите в директорию проекта:
    ```sh
    cd process-management-system
    ```

3. Установите зависимости через Composer:
    ```sh
    composer install
    ```

4. Настройте подключение к базе данных в файле `config/database.php`:
    ```php
    return [
        'host' => 'localhost',
        'database' => 'test_db',
        'user' => 'root',
        'password' => ''
    ];
    ```

5. Создайте таблицы в базе данных, выполнив SQL-запросы из файла `database/schema.sql`.

## Использование

Пример использования классов для создания процесса и добавления полей:

```php
require 'vendor/autoload.php';

use App\Database;
use App\Field;
use App\Process;

$config = require 'config/database.php';
$db = new Database($config);

// Создание процесса и добавление полей
$process = new Process('Test Process');
$process->addField(new Field('Text', 'text', 'Sample Text'));
$process->addField(new Field('Number', 'number', 123, '%d'));
$process->addField(new Field('Date', 'date', '2024-06-11', 'Y-m-d'));

$process->save($db);