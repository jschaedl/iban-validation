COMPOSER_BIN := composer
PHP_BIN := php
PHIVE_BIN := phive
PHP_CS_FIXER_BIN := ./tools/php-cs-fixer
PHPSTAN_BIN	:= ./tools/phpstan
PHPUNIT_BIN	:= ./tools/phpunit


.PHONY: it
it: tools-install qa

.PHONY: tools-install
tools-install:
	gpg --keyserver hkps://keyserver.ubuntu.com --receive-keys E82B2FB314E9906E 4AA394086372C20A 51C67305FFC2E5C0
	$(PHIVE_BIN) --no-progress install --copy --trust-gpg-keys E82B2FB314E9906E,4AA394086372C20A,51C67305FFC2E5C0 --force-accept-unsigned

.PHONY: tools-update
tools-update: tools-install
	$(PHIVE_BIN) --no-progress update

vendor: composer.json $(wildcard composer.lock)
	$(COMPOSER_BIN) install --optimize-autoloader

.PHONY: tests tests-coverage php-cs-check php-cs-fix phpstan
tests: vendor
	$(PHPUNIT_BIN) -c .

.PHONY: coverage
coverage: vendor
	XDEBUG_MODE=coverage $(PHPUNIT_BIN) -c . --coverage-html coverage

.PHONY: cs-check
cs-check: vendor
	PHP_CS_FIXER_FUTURE_MODE=1 PHP_CS_FIXER_IGNORE_ENV=1 $(PHP_CS_FIXER_BIN) fix --allow-risky=yes --diff --using-cache=no --verbose --dry-run

.PHONY: cs-fix
cs-fix: vendor
	PHP_CS_FIXER_FUTURE_MODE=1 PHP_CS_FIXER_IGNORE_ENV=1 $(PHP_CS_FIXER_BIN) fix --allow-risky=yes

.PHONY: analyze
analyze: vendor
	$(PHPSTAN_BIN) analyse --memory-limit=-1

.PHONY: qa
qa: cs-check analyze tests
