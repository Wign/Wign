#!/bin/bash
clear

DOCKERID=$(docker ps | grep "wign:app" | awk '{print $1}')
docker exec -it $DOCKERID bash
