echo "Iniciando setup"

composer require laravel/sail --dev
./vendor/bin/sail up -d
./vendor/bin/sail composer install
cp .env.example .env
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh
./vendor/bin/sail artisan test