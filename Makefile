refresh:
	php artisan migrate:refresh --seed
	php artisan passport:client --personal

api\:doc\:gen:
	php artisan l5-swagger:generate

tests:
	vendor/bin/phpunit --testdox --colors --stop-on-failure

tests\:coverage:
	vendor/bin/phpunit --testdox --colors --stop-on-failure --coverage-html=tests/coverage
