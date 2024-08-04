build:
	docker-compose build --no-cache --force-rm
down:
	docker-compose down
up:
	docker-compose up -d
in laravel:
	docker exec -it barber-shop bash