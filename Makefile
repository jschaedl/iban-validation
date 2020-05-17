help: ## Displays this list of targets with descriptions
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

it: coding-standards static-code-analysis tests ## Runs the coding-standards, static-code-analysis, and tests targets

coding-standards: ## Run php-cs-fixer
	vendor/bin/php-cs-fixer fix --diff --diff-format=udiff --verbose

static-code-analysis: ## Run phpstan
	vendor/bin/phpstan analyze

tests: ## Run phpunit
	vendor/bin/phpunit

.PHONY: help coding-standards static-code-analysis tests
