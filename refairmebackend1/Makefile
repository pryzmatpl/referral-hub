# Copyright: PRIZM Piotr Słupski 2023

clean: stopall cleanvolumes pruneall

up:
	docker compose -f docker-compose.yml build --no-cache && docker compose -f docker-compose.yml up -d

stopall:
	docker compose down --remove-orphans -v

cleanvolumes:
	docker volume prune -f

pruneall:
	docker system prune --all -f

