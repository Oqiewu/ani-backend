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

