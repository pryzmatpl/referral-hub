# Copyright: PRIZM Piotr Słupski 2025
.PHONY: debug prod

debug:
	ENV=debug $(MAKE) -C refrme-frontend dev
	ENV=debug $(MAKE) -C refrme-backend debug

prod:
	ENV=prod $(MAKE) -C refrme-frontend prod
	ENV=prod $(MAKE) -C refrme-backend prod

down:
	ENV=prod $(MAKE) -C refrme-frontend down
	ENV=prod $(MAKE) -C refrme-backend down