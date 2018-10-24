.PHONY: cs deptrac test

it: cs deptrac test

cs: vendor
	vendor/bin/php-cs-fixer fix --config=.php_cs --diff --verbose

deptrac:
	docker-compose run --rm devtools deptrac analyze --formatter-graphviz-dump-image=/opt/var/dependency-graph.png

test:
	docker-compose up -d
	docker-compose run --rm devtools /bin/bash -c "vendor/bin/phpunit && vendor/bin/behat -v"

vendor: composer.json composer.lock
	composer self-update
	composer validate
	composer install
