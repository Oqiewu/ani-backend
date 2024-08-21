# Ani Backend

<!-- Блок информации о репозитории в бейджах -->
![Static Badge](https://img.shields.io/badge/Oqiewu-Ani-backend)
![GitHub top language](https://img.shields.io/github/languages/top/Oqiewu/ani-backend)

## О проекте Ani

**Ani** — персональный гид по миру аниме, предлагающий интерфейс для просмотра контента и доступ к энциклопедическим данным. Проект включает в себя frontend и backend, обеспечивающий обработку данных и взаимодействие с базой данных, необходимой для работы приложения.

**Frontend** и **backend** части приложения разделены на два отдельных репозитория:
- [ani-frontend](https://github.com/Oqiewu/ani-frontend) — клиентская часть.
- [ani-backend](https://github.com/Oqiewu/ani-backend) — серверная часть (этот репозиторий).

## О серверной части (Ani Backend)

Ani Backend отвечает за:
- Обработку запросов от клиентской части.
- Управление базой данных.

### Основные технологии

- **Symfony 7** — серверный фреймворк.
- **MySQL** — база данных.
- **Docker** — контейнеризация приложения.

### Установка и запуск


1. Склонируйте репозиторий:
   ```bash
   git clone https://github.com/Oqiewu/ani-backend.git
   ```

2. Перейдите в директорию проекта (здесь находится `docker-compose.yaml`):
   ```bash
   cd ani-backend
   ```
3. Создайте и запустите контейнеры:
   ```bash
   docker-compose up -d
   ```
   Эта команда выполнит сборку Docker-образов и запустит все необходимые контейнеры в фоновом режиме.

4. Проверьте работу приложения:
   
   Приложение будет доступно по адресу `http://localhost:8080`.

### Архитектура проекта

Проект использует подход **модульного монолита**. Этот подход позволяет сохранять целостность приложения, разделяя код на независимые модули, которые могут развиваться и тестироваться отдельно, но при этом работать в рамках одного монолитного приложения.

#### Основные директории

- `src/Command/` — команды консоли.
- `src/Controller/` — контроллеры, не привязанные к конкретным модулям.
- `src/EventListener/` — обработчики событий.
- `src/Module/` — директория, содержащая модули, каждый из которых имеет свою структуру:
  - `Controller/` — контроллеры модуля.
  - `DTO/` — объекты передачи данных (Data Transfer Objects).
  - `Entity/` — сущности Doctrine.
  - `Enum/` — перечисления (Enums), используемые в модуле.
  - `Repository/` — взаимодействие с базой данных.
  - `Service/` — бизнес-логика модуля.
  


Такое разделение позволяет каждому модулю быть максимально автономным и легко расширяемым.

### Скрипты и команды
- `bin/console` — основной интерфейс для работы с консольными командами Symfony.
- `docker-compose` — управление контейнерами Docker.
- `app:cache-anime-titles` — кэширование аниме-тайтлов через общедоступный API.
- `app:sync-anime-titles` — синхронизация кэшированных аниме-тайтлов с базой данных.
