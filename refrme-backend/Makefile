# Copyright: PRIZM Piotr Słupski 2024

purge: down clean prune

prod:
	docker-compose -f docker-compose.yml build && docker-compose -f docker-compose.yml up -d

debug:
	docker-compose -f docker-compose-debug.yml build && docker-compose -f docker-compose-debug.yml up -d

cleandb:
	docker exec -it refrme-backend-aimatch-1 php ./cli.php cleandb

migrate:
	docker exec -it refrme-backend-aimatch-1 php ./cli.php migrate

seed:
	docker exec -it refrme-backend-aimatch-1 php ./cli.php seed

test:
	docker exec -it refrme-backend-aimatch-1 php ./vendor/bin/codecept build && \
	docker exec -it refrme-backend-aimatch-1 php ./vendor/bin/codecept run

down:
	docker-compose down --remove-orphans -v

clean:
	docker volume prune -f

prune:
	docker system prune --all -f

