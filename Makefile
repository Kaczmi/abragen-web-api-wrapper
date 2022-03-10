run-tests:
	make composer
	./vendor/bin/tester -s -C ./tests

composer:
	composer install

phpstan:
	./vendor/bin/phpstan analyse --level 7 src

cs:
	./vendor/bin/phpcs --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests

cs-fix:
	./vendor/bin/phpcbf --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests
