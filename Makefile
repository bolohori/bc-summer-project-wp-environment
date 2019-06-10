# Makefile for WordPress Summer Project Environment

include .env

init:
	@docker exec -it web "composer install"
	@npm install

docker-start:
	@docker-compose up web

docker-stop:
	@docker-compose down -v

logs:
	@docker-compose logs -f

rm-containers:
	@docker stop $(shell docker ps -a -q)
	@docker rm $(shell docker ps -a -q)

shell:
	@docker-compose run shell

watch:
	@npm run watch

build:
	@npm run build