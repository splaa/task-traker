<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## Проверка зависимостей и тесты

Для сборки используйте Node 22 (`nvm use`); legacy CLI Laravel Mix 6 не работает
с Node 26. PHP-тесты запускаются на PHP 8.2 и 8.5 с `pdo_sqlite`.
Docker/OrbStack для этих проверок не требуется.

```sh
composer install --no-interaction --prefer-dist
cp .env.example .env # только при первом запуске, не перезаписывайте свой .env
php artisan key:generate
composer validate --strict
composer audit --locked
vendor/bin/phpunit
npm ci
npm audit --audit-level=high
npm run test:security
npm run production
```

`test:security` проверяет shell-quote и работу webpack-dev-server с исправленным
middleware, включая запрет чтения файлов вне output-каталога. Тест временно
открывает сервер только на `127.0.0.1`. PHP-регрессия проверяет, что TokenGuard
не передаёт нестроковые токены в провайдер пользователей.

Override `webpack-dev-middleware` на `^7.4.6` необходим для
[GHSA-g84c-rxfj-3j2c](https://github.com/webpack/webpack-dev-middleware/security/advisories/GHSA-g84c-rxfj-3j2c):
Laravel Mix 6 через webpack-dev-server 4 иначе устанавливает уязвимую ветку 5.x.
Его совместимость проверяется regression-тестом и production-сборкой в CI.
React Refresh зависимости явно записаны в lockfile, чтобы `npm run hot`
не устанавливал незакреплённые пакеты при первом запуске.

На 14 ноября 2026 года Composer audit чистый; npm audit сообщает 0 critical,
0 high, 8 moderate и 5 low (все в dev-цепочке Mix). Остаются advisory для
`qs`/Express, `uuid`/уведомлений/SockJS, webpack-dev-server и browserify/elliptic.
Полный `npm audit` продолжает показывать их; исключений advisory нет.
Для полного устранения нужен отдельный пересмотр устаревшего frontend toolchain.
Порог CI `high` относится только к уровню отказа команды, а не к скрытию отчёта.

[Back-End. Тестовое задание «Task Tracker»](https://www.notion.so/Back-End-Task-Tracker-4d0ad2be40aa4b7b8732e7c0b60e7fec)

## Технические требования:

- [x] Должен быть предоставлен API.

 - [API Tasks](https://github.com/splaa/task-traker/blob/master/bin/http/heroku-tasks.http)
 - [API Users](https://github.com/splaa/task-traker/blob/master/bin/http/heroku-user.http)
        
- [x] Результат API запросов должен быть в виде JSON.
- [x] Результат задания должен быть выложен на github.
    - [приложение Task Tracker GitHub](https://github.com/splaa/task-traker)
    - [приложение Task Tracker Deploy Heroku](http://task-traker.herokuapp.com/)
- [x] Используется база данных MySQL.
       



# **Тестовое задание**

- [x] Создать приложение Task Tracker с управлением задачами через API.

## Приложение должно содержать:

- [x] Задачи
- [x] Список пользователей на которых можно назначить задачу

## Возможные действия через API:

### Пользователь

- [x] Создание пользователя
- [x] Редактирование пользователя
- [x] Удаление пользователя
- [x] Получение всех пользователей

Обязательные поля для пользователя в базе данных:
```json
{
  "user_id": 1,
  "first_name": "",
  "last_name": "",
  ...
}
```

### Задача

- [x] Создание задачи
- [x] Редактирование задачи
- [x] Изменить статус задачи
- [x] Удаление задачи
- [x] Получение списка задач

    Можно не делать, но будет плюсом следующее:

    - [x] Отфильтровав по status
    - [x] Отсортировав по id
-[x] Изменить пользователя на которого назначена задача

Обязательные поля для задачи в базе данных:
```json
{
  "id": 1,
  "title": "",
  "description": "",
	"status": "" // ["View", "In Progress", "Done"]
  ...
}
```

