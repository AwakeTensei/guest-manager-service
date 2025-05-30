# Guest Manager Service

Данный проект представляет собой API-микросервис для управления записями о гостях. 
Он позволяет создавать, получать, обновлять и удалять информацию о гостях, включая их имя, фамилию, email, телефон и страну. 
Проект реализован на Laravel 11, PHP 8.3 и в качестве БД использует PostgreSQL. 

## Архитектура проекта

В качестве архитектуры использованы паттерны MVC + DDD, компоненты разделены на слои (Controller, Service, Repository). 
Для работы с БД использованы RAW-SQL запросы.

Структура по компонентам представлена ниже:
- Controller (GuestController): Тонкий контроллер, обрабатывает HTTP-запросы и возвращает ответы.
- Service (GuestService): Содержит бизнес-логику (в данном случае определение страны по номеру телефона).
- Repository (GuestRepository): Работает с базой данных через Raw SQL-запросы.
- Middleware (PerfomanceHeaders): Добавляет заголовки X-Debug-Time и X-Debug-Memory для отладки.
- Requests (StoreGuestRequest, UpdateGuestRequest): Валидация входящих данных.

### Основные особенности

- **REST API**: Реализованы CRUD-эндпоинты для работы с гостями.
- **Docker**: Проект упакован в Docker-контейнер (Nginx, PHP-FPM, PostgreSQL).
- **Тесты**: Реализованы feature-тесты для проверки функциональности API.
- **Валидация**: Используются Form Requests для валидации входящих данных.
- **Определение страны**: Страна гостя определяется автоматически по номеру телефона, если не указана явно (актуально для RU, USA, UK).

## Эндпоинты

GET - /api/guests - Получить список всех гостей

Тело отсутствует

----------------
POST - /api/guests - Создать нового гостя

Тело и доступные поля(JSON):

{"first_name": "Van", 
"last_name": "Ivanov", 
"phone": "+79624567890", 
"email": "van.ivanov@example.ru", 
"country": "Russia"}

----------------
GET - /api/guests/{id} - Получить гостя по ID

Тело отсутствует

----------------
PUT - /api/guests/{id} - Обновить данные гостя

Тело и доступные поля(JSON):

{"first_name": "Van", 
"last_name": "Ivanov", 
"phone": "+79624567890", 
"email": "van.ivanov@example.ru", 
"country": "Russia"}

----------------
DELETE - /api/guests/{id} - Удалить гостя по ID

Тело отсутствует

----------------
### Примечания к эндпоинтам

- Все ответы содержат заголовки:
  - `X-Debug-Time` — время выполнения запроса (в миллисекундах).
  - `X-Debug-Memory` — объём использованной памяти (в килобайтах).
- Для `POST /api/guests`:
  - **Обязательные параметры**: `first_name`, `last_name`, `phone`.
  - **Необязательные параметры**: `email`, `country`. Если `country` не указано, оно определяется автоматически по номеру телефона (например, `+7` → `Russia`).
- Для `PUT /api/guests/{id}`:
  - Все параметры необязательные. Указывайте только те поля, которые нужно обновить.
- Поле `email` должно быть уникальным (если указано).
- Поле `phone` должно быть уникальным и соответствовать формату `+<код страны><номер>` (например, `+79624567890`).

- **id** является AUTOINCREMENT PRIMARY KEY , поэтому формируется автоматически для каждого гостя.

## Установка и запуск

Следуйте этим шагам, чтобы поднять проект локально:
1. **Склонируйте репозиторий**:
   ```bash
   git clone https://github.com/AwakeTensei/guest-manager-service.git
   ```
   ```bash
   cd guest-manager-service
   ```
2. **Установите зависимости через Composer**:
    ```bash
    composer install
    ```
3. **Отредактируйте .env.example**:
    Измените необходимые параметры и
    переименуйте .env.example в .env
   
    APP_KEY изменится после следующего шага автоматически.
   
5. **Сгенерируйте ключ приложения**:
    ```bash
    php artisan key:generate
    ```
    Проверьте APP_KEY в .env

8. **Запустите контейнеры**:
    ```bash
    docker-compose up -d --build
    ```
    Если возникает ошибка "the attribute version is obsolete",
    то просто уберите строку version 3.8 из docker-compose.yml
10. **Выполните миграции**:
    ```bash
    docker-compose exec app php artisan migrate
    ```
12. **Можно запустить тесты для проверки функциональности приложения**
    ```bash
    docker-compose exec app php artisan test
    ```
Приложение будет доступно по адресу http://localhost:8080.

------------------------------
**Проверяем эндпоинты при помощи curl**

- Создаем гостя

Linux
```bash
curl -X POST http://localhost:8080/api/guests -H "Content-Type: application/json" -d '{"first_name":"Van","last_name":"Ivanov","phone":"+79624567890","email":"van.ivanov@example.ru"}'
```
Windows (powershell)
```bash
Invoke-WebRequest -Uri http://localhost:8080/api/guests -Method Post -Headers @{ "Content-Type" = "application/json" } -Body '{"first_name":"Van","last_name":"Ivanov","phone":"+79624567890","email":"van.ivanov@example.ru"}'
```
- Ответ:
    {"message":"Guest created"}

------------------------------
- Получаем гостя по ID

Linux
```bash
curl http://localhost:8080/api/guests/1
```
Windows (powershell)
```bash
Invoke-WebRequest -Uri http://localhost:8080/api/guests/1 -Method Get
```
- Пример ответа:
    {
        "id": 1,
        "first_name": "Van",
        "last_name": "Ivanov",
        "email": "van.ivanov@example.ru",
        "phone": "+79624567890",
        "country": "Russia",
        "created_at": "2025-03-26T21:44:45.000000Z",
        "updated_at": "2025-03-26T21:44:45.000000Z"
    }

------------------------------
- Обновляем данные гостя по ID

Linux
```bash
curl -X PUT http://localhost:8080/api/guests/1 -H "Content-Type: application/json" -d '{"first_name":"Ivan","last_name":"Sokolov","phone":"+79624567890","email":"ivan.ivanov@example.ru"}'
```
Windows (powershell)
```bash
Invoke-WebRequest -Uri http://localhost:8080/api/guests/1 -Method Put -Headers @{ "Content-Type" = "application/json" } -Body '{"first_name":"Ivan","last_name":"Sokolov","phone":"+79624567890","email":"ivan.ivanov@example.ru"}'
```
- Ответ:
    {"message":"Guest updated"}

------------------------------
- Удаляем данные гостя по ID

Linux
```bash
curl -X DELETE http://localhost:8080/api/guests/1
```
Windows (powershell)
```bash
Invoke-WebRequest -Uri http://localhost:8080/api/guests/1 -Method Delete
```
- Ответ:
    {"message":"Guest deleted"}
