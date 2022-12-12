COMPOSER_BIN := composer
PHP_BIN := php
PHIVE_BIN := phive
PHP_CS_FIXER_BIN := ./tools/php-cs-fixer
PHPSTAN_BIN	:= ./tools/phpstan
PHPUNIT_BIN	:= ./tools/phpunit.phar

.PHONY: it tools-install composer-install tests tests-coverage php-cs-check php-cs-fix phpstan

it: tools-install tests

tools-install:
	gpg --keyserver hkps://keyserver.ubuntu.com --receive-keys E82B2FB314E9906E 4AA394086372C20A 8E730BA25823D8B5 CF1A108D0E7AE720 8A03EA3B385DBAA1
	$(PHIVE_BIN) --no-progress install --copy --trust-gpg-keys E82B2FB314E9906E,4AA394086372C20A,8E730BA25823D8B5,CF1A108D0E7AE720,8A03EA3B385DBAA1 --force-accept-unsigned

composer-install:
	$(COMPOSER_BIN) install --optimize-autoloader

tests: composer-install
	$(PHPUNIT_BIN) -c .

tests-coverage: composer-install
	XDEBUG_MODE=coverage $(PHPUNIT_BIN) -c . --coverage-html coverage

cs-check:
	PHP_CS_FIXER_FUTURE_MODE=1 PHP_CS_FIXER_IGNORE_ENV=1 $(PHP_CS_FIXER_BIN) fix --allow-risky=yes --diff --using-cache=no --verbose --dry-run

cs-fix:
	PHP_CS_FIXER_FUTURE_MODE=1 PHP_CS_FIXER_IGNORE_ENV=1 $(PHP_CS_FIXER_BIN) fix --allow-risky=yes

analyze:
	$(PHPSTAN_BIN) analyse --memory-limit=-1
