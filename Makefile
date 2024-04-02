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
	docker exec laravel-vue bash -c "composer update"
data:
	docker exec laravel-vue bash -c "php artisan migrate"
	docker exec laravel-vue bash -c "php artisan db:seed"
cache:
	docker exec laravel-vue bash -c "php artisan config:cache"
	docker exec laravel-vue bash -c "php artisan config:clear"
	docker exec laravel-vue bash -c "php artisan cache:clear"

