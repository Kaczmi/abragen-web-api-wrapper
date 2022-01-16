run-tests:
	make composer
	.\vendor\bin\tester -s -C .\tests

composer:
	composer install

phpstan:
	.\vendor\bin\phpstan analyse --level 7 src