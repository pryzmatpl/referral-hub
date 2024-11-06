# Copyright: PRIZM Piotr Słupski 2023

purge: stopall clean prune

up:
	docker-compose -f docker-compose.yml build && docker-compose -f docker-compose.yml up -d

stopall:
	docker-compose down --remove-orphans -v

clean:
	docker volume prune -f

prune:
	docker system prune --all -f

