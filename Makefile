.PHONY: up down app csf punit stan deptrac

# start the containers with docker compose
up:
	docker-compose -f .local/docker-compose.yml up

# stop the containers with docker compose
down:
	docker-compose -f .local/docker-compose.yml down

# enter with terminal in station_php container
app:
	docker-compose -f .local/docker-compose.yml exec library_php /bin/bash

# run php code sniffer and fix issues
csf:
	./vendor/bin/phpcbf

# run tests with PHPUnit
punit:
	php ./vendor/bin/phpunit

# run static analyse of your code with PHPStan
stan:
	./vendor/bin/phpstan analyse

deptrac:
	./vendor/bin/deptrac
