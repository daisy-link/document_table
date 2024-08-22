gulp:
	cd coding && \
	node_modules/.bin/gulp
up-build:
	docker compose up --build
up:
	docker compose up
down:
	docker compose down
ssh:
	docker compose exec -u www-data app bash