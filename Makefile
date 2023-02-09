.PHONY : build
build:
	@ docker-compose build --no-cache
	@ docker-compose up -d

.PHONY : shell
shell:
	@ docker-compose exec php-fpm sh


.PHONY : test
test:
	@ docker-compose exec php-fpm php bin/console doctrine:migrations:migrate --env=test
	@ docker-compose exec php-fpm php bin/phpunit tests

.PHONY : db
db:
	@ docker-compose exec database sh -c "mysql -u app -p"

.PHONY : migrate
migrate:
	@ docker-compose exec php-fpm php bin/console doctrine:migrations:migrate