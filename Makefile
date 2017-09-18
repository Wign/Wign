build: .built

up: build vendor
	docker-compose up

.built: Dockerfile
	docker build -t wign:app \
		--build-arg userid=$(shell id -u) \
		--build-arg groupid=$(shell id -g) .
	touch .built

vendor:
	CID=$(shell docker run -d wign:app tail -f /dev/null) ; \
	docker cp $$CID:/var/www/vendor ./vendor ; \
	docker rm -f $$CID

aws-shell:
	docker-compose run aws bash

clean:
	rm .built
.PHONY: aws-shell
