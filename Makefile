migrate-heroku:
	heroku run  php artisan migrate
refresh-migrate-heroku:
	heroku run  php artisan migrate:refresh --seed

run-local-server:
	php artisan serv
