# Дампер таблиц и данных MySQL

## Принцип работы
```sh
php ./export.php
```
запускает экспорт структуры и данных таблиц из целевой бд в разные папки. На каждую таблицу отдельный файл.

```sh
php ./import.php
```
запускает импорт структуры и данных из файлов, полученных после экспорта.

## Подготовка перед запуском

Создать конфигурационные файлы:
config.inc.php - в нем указать путь к утилитам mysql и mysqldump на вашем компьютере

export.connection.config.inc.php - указать параметры подключения к бд из которой копировать данные

import.connection.config.inc.php - указать параметры подключения к бд, в которую загрузить данные
