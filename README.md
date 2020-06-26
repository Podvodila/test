<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## Install

- `composer install`
- `yarn install`
- `cp .env.example .env` and add your DB credentials and app URL
- `php artisan key:generate`
- `php artisan migrate`
- `php artisan articles:store-from-url {url}` to fill database with articles
<br>E.g. 200 articles URL - https://raw.githubusercontent.com/SeteMares/full-stack-test/master/feed.json
- `php artisan serve` and open browser
