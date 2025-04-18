# Copyright: PRIZM Piotr Słupski 2025

.PHONY: debug prod down

# Helper function to check if a container is running
container_running = docker ps --format '{{.Names}}' | grep -q $(1)

debug:
	@if docker ps --format '{{.Names}}' | grep -q 'aimatch'; then \
		echo "Aimatch container already running. Skipping backend debug..."; \
	else \
		NODE_ENV=debug $(MAKE) -C refrme-backend debug; \
	fi
	@if docker ps --format '{{.Names}}' | grep -q 'frontend'; then \
		echo "Frontend container already running. Skipping frontend debug..."; \
	else \
		NODE_ENV=debug $(MAKE) -C refrme-frontend dev; \
	fi

prod:
	@if docker ps --format '{{.Names}}' | grep -q 'frontend'; then \
		echo "Frontend container already running. Skipping frontend prod..."; \
	else \
		NODE_ENV=prod $(MAKE) -C refrme-frontend prod; \
	fi
	@if docker ps --format '{{.Names}}' | grep -q 'aimatch'; then \
		echo "Aimatch container already running. Skipping backend prod..."; \
	else \
		NODE_ENV=prod $(MAKE) -C refrme-backend prod; \
	fi

prodmap:
	@if docker ps --format '{{.Names}}' | grep -q 'frontend'; then \
		echo "Frontend container already running. Skipping frontend prod..."; \
	else \
		NODE_ENV=prod $(MAKE) -C refrme-frontend build:sourcemap; \
	fi
	@if docker ps --format '{{.Names}}' | grep -q 'aimatch'; then \
		echo "Aimatch container already running. Skipping backend prod..."; \
	else \
		NODE_ENV=prod $(MAKE) -C refrme-backend prod; \
	fi

down:
	$(MAKE) -C refrme-frontend down
	$(MAKE) -C refrme-backend down
