@echo off

docker exec -it --user root --workdir /app php-corbomite-user bash -c "composer %*"
