# Copyright: PRIZM Piotr Słupski 2025
.PHONY: debug prod

debug:
	ENV=debug $(MAKE) -C refrme-backend debug
	ENV=debug $(MAKE) -C refrme-frontend dev

prod:
	ENV=prod $(MAKE) -C refrme-backend prod
	ENV=prod $(MAKE) -C refrme-frontend prod

down:
	ENV=prod $(MAKE) -C refrme-frontend down
	ENV=prod $(MAKE) -C refrme-backend down