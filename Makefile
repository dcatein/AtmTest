.PHONY: up create-db run install env
up:
	docker-compose up -d laravel.test db redis mailhog
create-db:
 	./vendor/bin/sail artisan migrate:fresh
install:
	docker-compose up -d composer
env:
	cp .env.example .env
test:
	./vendor/bin/sail artisan test
run:
	make up
	./vendor/bin/sail artisan key:generate
	make create-db
	make test