migrate-heroku:
	heroku run  php artisan migrate
refresh-migrate-heroku-seed:
	heroku run  php artisan migrate:refresh --seed
refresh-migrate-heroku:
	heroku run  php artisan migrate:refresh

run-local-server:
	php artisan serv
prod:
	heroku config:add APP_ENV=production
dev:
	heroku config:add APP_ENV=local
