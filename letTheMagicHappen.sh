#!/bin/bash
clear
docker-compose exec app php ./vendor/bin/phpunit --printer=Codedungeon\\PHPUnitPrettyResultPrinter\\Printer