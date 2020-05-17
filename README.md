<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

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

