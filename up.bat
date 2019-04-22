@echo off

docker-compose -f docker-compose.yml -p corbomite-user up -d
docker exec -it --user root --workdir /app php-corbomite-user bash -c "cd /app && composer install"
