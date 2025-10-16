.PHONY: help install test analyse fix docs metrics clean

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

install: ## Install dependencies
	composer install

update: ## Update dependencies
	composer update

test: ## Run tests
	composer test

test-coverage: ## Run tests with coverage
	composer test:coverage

analyse: ## Run static analysis
	composer analyse

phpstan: ## Run PHPStan
	composer phpstan

psalm: ## Run Psalm
	composer psalm

phpcs: ## Check code style
	composer phpcs

phpmd: ## Run PHP Mess Detector
	composer phpmd

fix: ## Fix code style and refactor
	composer fix

php-cs-fixer: ## Fix code style with PHP-CS-Fixer
	composer php-cs-fixer:fix

rector: ## Refactor code with Rector
	composer rector:fix

infection: ## Run mutation testing
	composer infection

metrics: ## Generate code metrics
	composer metrics

docs: ## Generate documentation
	composer docs

benchmark: ## Run benchmarks
	composer benchmark

check: ## Run all checks
	composer check

clean: ## Clean generated files
	rm -rf vendor/ coverage/ build/ docs/api/ .phpunit.cache/ .php-cs-fixer.cache

ci: install analyse test ## Run CI pipeline

