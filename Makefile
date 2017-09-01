build: .built

up: build
	docker-compose up

.built: Dockerfile
	docker build -t wign:app \
		--build-arg userid=$(shell id -u) \
		--build-arg groupid=$(shell id -g) .
	touch .built

aws-shell:
	docker-compose run aws bash

clean:
	rm .built
.PHONY: aws-shell
