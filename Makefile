NODE_MODULES = ./node_modules
VENDOR = ./vendor

init: pull build up composer-install migrate fixtures

pull:
	docker compose pull

build:
	docker compose build

up:
	docker compose up -d

composer-install:
	docker compose run --rm php-fpm composer install

migrate:
	docker compose run --rm php-fpm php bin/console doctrine:migrations:migrate --no-interaction

fixtures:
	docker compose run --rm php-fpm php bin/console hautelook:fixtures:load --no-interaction

##
# UTILS
##
clean-log:
	rm -rf ./var/log

clean-cache:
	rm -rf ./var/cache

watch:
	npm run watch

##
# REFACTORING
##

check:
	make refactoring --keep-going

refactoring: eslint php-cs-fixer

eslint:
	${NODE_MODULES}/.bin/eslint assets/js/ --ext .js,.vue --fix

php-cs-fixer:
	${VENDOR}/bin/php-cs-fixer fix src/  --verbose

phpstan:
	${VENDOR}/bin/phpstan analyse src --level 4
