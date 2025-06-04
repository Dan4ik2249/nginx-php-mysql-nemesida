Данный репозиторий содержит простой сайт с формой логина и возможностью добавления записей для пользователя (оригинальный сайт был скачан где-то на просторах github).
Связка nginx+php+mysql разворачивается с помощью docker-compose.

Для корректной развертки необходимо выполнить некоторые действия.

1)mkdir -p /opt/nwaf/{waf-config,mlc-config,nwaf-api,nwaf-cabinet,nwaf-scanner,nwaf-db}

2)touch /opt/nwaf/{waf-config,mlc-config,nwaf-api,nwaf-cabinet,nwaf-scanner}/first-launch

3)docker-compose up --build -d

4) (В случае проблем)

chmod -R 0555 /opt/nwaf/nwaf-api

chmod -R 0555 /opt/nwaf/nwaf-cabinet

5)docker-compose exec nwaf-cabinet "/bin/bash" "/opt/migrate.sh"

6)docker-compose down

7)Засунуть в /opt/nwaf/waf-config/conf.d файлик site.conf

8)docker-compose up -d
