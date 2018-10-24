.PHONY: cs

it: cs

cs: vendor
	vendor/bin/php-cs-fixer fix --config=.php_cs --diff --verbose

vendor: composer.json composer.lock
	composer self-update
	composer validate
	composer install
