### Slotegrator-Test-Task

The repository contains code for generating random prizes

#### Tech Stack
- PHP 7.4
- Nginx 1.17.8
- MySQL 8.0

#### Packages
- [php-di/php-di](https://github.com/PHP-DI/PHP-DI)
- [nikic/fast-route](https://github.com/nikic/FastRoute)
- [doctrine/dbal](https://github.com/doctrine/dbal)
- [patricklouys/http](https://github.com/PatrickLouys/http)

#### Installation
`$ make up`

#### Run tests
`$ make test`

#### Usage
|url                |example                        |description                         |
|------------|-------------------------------|-----------------------------|
|GET {{HOST}}/api/v1/prizes|`curl -H 'X-UserID: {{USER_ID}}' {{HOST}}/api/v1/prizes`            | Get list of prizes           |
|POST {{HOST}}/api/v1/prize/draw          |`curl -X POST -H 'X-UserID: {{USER_ID}}' '127.0.0.1:8080/api/v1/prize/draw'`            |Get random prize           |
|POST {{HOST}}/api/v1/prize/accept     |`curl -X POST -H 'X-UserID: {{USER_ID}}' '127.0.0.1:8080/api/v1/prize/accept?prize_id={{PRIZE_ID}}&accept=1'`|Accept(1) or decline(0) received prize|
|POST {{HOST}}/api/v1/prize/transfer     |`curl -X POST -H 'X-UserID: {{USER_ID}}' '127.0.0.1:8080/api/v1/prize/transfer?prize_id={{PRIZE_ID}}&needs_convertation=1'`| Transfer `Bonus` to user's loyalty account or `Money` to user's bank account. Doesn't work for `Items` type prizes. Add `needs_convertation=1|0` query param if user needs to conver `	`Money` to `Bonus`|
|POST {{HOST}}/api/v1/prize/delivery     |`curl -X POST -H 'X-UserID: {{USER_ID}}' '127.0.0.1:8080/api/v1/prize/delivery?prize_id={{PRIZE_ID}}'`| Order delivery for the prize. Works only for `Item` type prizes|

