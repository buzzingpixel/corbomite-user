@echo off

docker-compose up -d
docker exec -it --user root --workdir /app php-corbomite-user bash -c "php %*"
