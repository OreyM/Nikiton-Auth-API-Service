refresh:
	php artisan migrate:refresh --seed
	php artisan passport:client --personal

tests:
	vendor/bin/phpunit --testdox --colors --stop-on-failure

tests\:coverage:
	vendor/bin/phpunit --testdox --colors --stop-on-failure --coverage-html=tests/coverage
