# setup:
# 	@make build
# 	@make up
# 	@make composer-update
# ps:
# 	docker-compose ps
# build:
# 	docker-compose build --no-cache --force-rm
# fresh:
# 	@make stop
# 	@make up
# stop:
# 	docker-compose stop
# up:
# 	docker-compose up -d
# composer-update:
# 	docker exec laravel-vue bash -c "composer update"
# data:
# 	docker exec laravel-vue bash -c "php artisan migrate"
# 	docker exec laravel-vue bash -c "php artisan db:seed"
clear:
	php artisan config:cache
	php artisan config:clear
	php artisan cache:clear
	php artisan route:clear
	php artisan view:clear
setup:
	@make build
	@make up
	@make composer-update
ps:
	docker-compose ps
build:
	docker-compose build --no-cache --force-rm
fresh:
	@make stop
	@make up
stop:
	docker-compose stop
up:
	docker-compose up -d
composer-update:
	docker exec laravel-app bash -c "composer update"
data:
	docker exec laravel-app bash -c "php artisan migrate"
	docker exec laravel-app bash -c "php artisan db:seed"
cache:
	docker exec laravel-app bash -c "php artisan config:cache"
	docker exec laravel-app bash -c "php artisan config:clear"
	docker exec laravel-app bash -c "php artisan cache:clear"

