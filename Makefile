up:
	docker-compose up -d

stop:
	docker-compose stop

destroy:
	docker-compose down

build:
	docker-compose up --build -d

test:
	docker-compose exec php_fpm ./vendor/bin/phpunit tests
