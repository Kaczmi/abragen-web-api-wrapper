run-tests:
	make composer
	.\vendor\bin\tester -s -C .\tests

composer:
	composer install