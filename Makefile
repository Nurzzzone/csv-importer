help: ## show this help
	@echo 'usage: make [target] ...'
	@echo ''
	@echo 'targets:'
	@egrep '^(.+)\:\ .*##\ (.+)' ${MAKEFILE_LIST} | sed 's/:.*##/#/' | column -t -c 2 -s '#'

up:
	docker-compose -f docker-compose.yml up -d --remove-orphans

build-up:
	docker-compose -f docker-compose.yml up -d --force-recreate --build --remove-orphans

composer-require:
	docker-compose exec app sh -c 'composer require $(p)'

composer-remove:
	docker-compose exec app sh -c 'composer remove $(p)'

composer-dumpautoload:
	docker-compose exec app sh -c 'composer dump-autoload'
