compose_file := "docker-compose.yml"
php_service := "php"

up:
	@docker-compose -f $(compose_file) up -d

composer:
	@docker-compose -f $(compose_file) exec $(php_service) sh -c "composer $(CMD)"

mysql:
	@docker-compose -f $(compose_file) exec $(php_service) sh -c "mysql -u root -psecret -h db $(CMD)"

doctrine:
	@docker-compose -f $(compose_file) exec $(php_service) sh -c "php doctrine.php $(CMD)"

fix:
	@docker-compose -f $(compose_file) exec $(php_service) sh -c "php-cs-fixer fix $(CMD)"
