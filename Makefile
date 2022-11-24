up:
	cp .env.example .env
	docker-compose up -d
	docker-compose exec php_fpm composer install
	docker-compose exec php_fpm vendor/bin/phinx migrate -c config/migration.php

stop:
	docker-compose stop

destroy:
	docker-compose down

test:
	docker-compose exec php_fpm ./vendor/bin/phpunit tests
