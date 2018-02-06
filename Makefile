.PHONY: tests rusty

tests:
	php ./vendor/bin/phpunit

rusty:
	php ./vendor/bin/rusty check --bootstrap-file=./vendor/autoload.php doc src
