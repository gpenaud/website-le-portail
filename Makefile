## Start, then log website stack locally
up:
	docker-compose up --detach
	docker-compose logs --follow website mysql

## Start, then log website stack locally, but force build first (without --no-cache option)
up-with-build:
	docker-compose up --build --detach
	docker-compose logs --follow website mysql

## Stop local website stack
down:
	docker-compose down --volumes

enter:
	docker-compose exec website bash

## Backups database from its development version
database-restore:
	docker cp services/mysql/dumps/development.sql $(shell docker-compose ps -q mysql):/development.sql
	docker-compose exec mysql sh -c "mysql -u root -proot leportaivfgam < development.sql"

## Install mkcert for self-signed certificates generation
certificates-install-mkcert:
	sudo apt install --yes libnss3-tools
	sudo wget -O /usr/local/bin/mkcert "https://github.com/FiloSottile/mkcert/releases/download/v1.4.3/mkcert-v1.4.3-linux-amd64" && chmod +x /usr/local/bin/mkcert
	mkcert -install

## Generate self-signed certificates
certificates-generate:
	mkcert -cert-file services/apache2/certificates/cert.pem -key-file services/apache2/certificates/key.pem www.leportail.localhost
	chmod 0644 services/apache2/certificates/key.pem

SHELL := /bin/bash
ONESHELL:

## permanent variables
PROJECT			?= github.com/gpenaud/website-le-portail
RELEASE			?= $(shell git describe --tags --abbrev=0)
COMMIT			?= $(shell git rev-parse --short HEAD)
BUILD_TIME  ?= $(shell date -u '+%Y-%m-%d_%H:%M:%S')

## Colors
COLOR_RESET       = $(shell tput sgr0)
COLOR_ERROR       = $(shell tput setaf 1)
COLOR_COMMENT     = $(shell tput setaf 3)
COLOR_TITLE_BLOCK = $(shell tput setab 4)

## display this help text
help:
	@printf "\n"
	@printf "${COLOR_TITLE_BLOCK}${PROJECT} Makefile${COLOR_RESET}\n"
	@printf "\n"
	@printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	@printf " make build\n\n"
	@printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	@awk '/^[a-zA-Z\-_0-9@]+:/ { \
				helpLine = match(lastLine, /^## (.*)/); \
				helpCommand = substr($$1, 0, index($$1, ":")); \
				helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
				printf " ${COLOR_INFO}%-15s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
		{ lastLine = $$0 }' $(MAKEFILE_LIST)
	@printf "\n"
