.PHONY: up create-db run install env
up:
	./vendor/bin/sail up -d laravel.test db redis mailhog
create-db:
 	./vendor/bin/sail artisan migrate:fresh
install:
	docker-compose up composer
env:
	cp .env.example .env
test:
	./vendor/bin/sail artisan test
run:
	make up
	./vendor/bin/sail artisan key:generate
	make create-db
	make test