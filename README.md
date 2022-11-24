### Slotegrator-Test-Task

The repository contains code for generating random prizes

### Tech Stack
- PHP 7.4
- Nginx 1.17.8
- MySQL 8.0

### Packages
- [php-di/php-di](https://github.com/PHP-DI/PHP-DI)
- [nikic/fast-route](https://github.com/nikic/FastRoute)
- [doctrine/dbal](https://github.com/doctrine/dbal)
- [patricklouys/http](https://github.com/PatrickLouys/http)
- [symfony/console](https://github.com/symfony/console)

### Installation
```bash
$ make run
```

### Run tests
```bash
$ make test
```

### API
|url                |example                        |description                         |
|------------|-------------------------------|-----------------------------|
|GET {{HOST}}/api/v1/prizes|`curl -H 'X-UserID: {{USER_ID}}'127.0.0.1:8080/api/v1/prizes`            | Get list of prizes           |
|POST {{HOST}}/api/v1/prize/draw          |`curl -X POST -H 'X-UserID: {{USER_ID}}' '127.0.0.1:8080/api/v1/prize/draw'`          |Get random prize           |
|POST {{HOST}}/api/v1/prize/accept     |`curl -X POST -H 'X-UserID: {{USER_ID}}' '127.0.0.1:8080/api/v1/prize/accept?prize_id={{PRIZE_ID}}&accept=1'`|Accept(1) or decline(0) received prize|
|POST {{HOST}}/api/v1/prize/transfer     |`curl -X POST -H 'X-UserID: {{USER_ID}}' '127.0.0.1:8080/api/v1/prize/transfer?prize_id={{PRIZE_ID}}&needs_convertation=1'`| Transfer `Bonus` to user's loyalty account or `Money` to user's bank account. Doesn't work for `Items` type prizes. Add `needs_convertation=1|0` query param if user needs to conver `	`Money` to `Bonus`|
|POST {{HOST}}/api/v1/prize/delivery     |`curl -X POST -H 'X-UserID: {{USER_ID}}' '127.0.0.1:8080/api/v1/prize/delivery?prize_id={{PRIZE_ID}}'`| Order delivery for the prize. Works only for `Item` type prizes|

### Console
| command                                     | example                                         | description                                  |
|---------------------------------------------|-------------------------------------------------|----------------------------------------------|
| php console user:create [name]              | `$ php console user:create 'Tomas'`             | Create a user                                |
| php console item:create [name]              | `$ php console item:create 'Iphone 5S'`         | Create an item                               |
| php console prizes:transfer [--batch-count] | `$ php console prizes:transfer --batch-count=5` | Transfer money prizes to user's bank account |

### Usage
```bash
$ git clone git@github.com:talgat065/slotegrator-test.git
$ cd slotegrator-test

$ make run
$ make test

$ docker-compose exec php_fpm php console user:create Paul

$ docker-compose exec php_fpm php console item:create 'Luna Controller with Phone Clip Bundle'
$ docker-compose exec php_fpm php console item:create 'Ring Alarm 8-piece kit (2nd Gen) with Ring Indoor Cam'
$ docker-compose exec php_fpm php console item:create 'Razer Anzu Smart Glasses'

$ curl -X POST -H 'X-UserID: {{USER_ID}}' '127.0.0.1:8080/api/v1/prize/draw' // repeat several times if needed
$ curl -X POST -H 'X-UserID: {{USER_ID}}' '127.0.0.1:8080/api/v1/prize/accept?prize_id={{PRIZE_ID}}&accept=1' // accept a prize
$ curl -X POST -H 'X-UserID: {{USER_ID}}' '127.0.0.1:8080/api/v1/prize/transfer?prize_id={{PRIZE_ID}}&needs_convertation=1' // transfer money to a bank account
$ curl -X POST -H 'X-UserID: {{USER_ID}}' '127.0.0.1:8080/api/v1/prize/delivery?prize_id={{PRIZE_ID}}' // order delivery for item prize

$ docker-compose exec php_fpm php console prizes:transfer --batch-count=10 // batch transfer money to user bank accounts
```
