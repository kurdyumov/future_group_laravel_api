# Тестовый REST API
[Техническое задание](https://github.com/fugr-ru/php)<br>
Исполнитель: Курдюмов Илья Константинович

## Введение

Данный репозиторий представляет из себя веб-сервис на языке PHP (веб-фреймворк Laravel)

## Как использовать

Убедитесь, что у вас на устройстве имеется платформа Docker для запуска контейнеров. Ниже вы можете найти docker-compose.yaml, который запускает следующий набор контейнеров:
- Laravel-проект;
- СУБД MySQL;
- Веб-интерфейс PhpMyAdmin.

Также не забудьте создать Dockerfile (пример содержимого можно найти ниже). Таким образом ваша директория должна иметь следующий вид:
- папка с Laravel-проектом;
- docker-compose.yaml;
- Dockerfile

Чтобы развернуть контейнеры, - откройте консоль, перейдите в место нахождения .yaml файла и выполните:
> docker-compose up -d

Имейте в виду, что папка с Laravel проектом должен быть на одном уровне с docker-compose.yaml и Dockerfile

## Пример docker-compose.yaml
```
services:
  app:
    build: .
    working_dir: /var/www/laravel
    volumes:
      - .:/var/www
    ports:
      - "9000:9000"
    depends_on:
      - db
    command: php -S 0.0.0.0:9000 -t public

  db:
    image: mysql:8.4
    volumes:
      - dbdata:/var/lib/mysql
    restart: always
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: laravel

  phpmyadmin:
    image: phpmyadmin:latest
    restart: always
    ports:
      - "8080:80"
    environment:
      PMA_HOST: db
      MYSQL_ROOT_PASSWORD: root

volumes:
  dbdata:
```

## Пример Dockerfile
```
FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd \
    && pecl install xdebug && docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/laravel

CMD ["php", "-S", "0.0.0.0:9000", "-t", "public"]
```
