# Live Donate

Service for accepting donations during streams

<img src="./twitchdonate.jpg" />

## Demo
[<img src="./twitchdonate.jpg">](https://github.com/YanGusik)

Viewers donate through my service, and the donation message pops up immediately on OBS!

## Requirements
 
* TDD conception of development
* Plugins and technologies:
  * Websockets without nodejs server
  * RabbitMQ - Message Broker
  * Laravel Passport - Authorization
  * JetStreem
* Active Directory support

## TODOs

* [ ] Websockets
* [ ] Api
* [ ] Laravel Auth:API
* [ ] OBS plugin
* [ ] Tests
* [ ] Add Dockerfile

## Testing

> Please, do not run tests on production environment!

Preconfigure your development environment

    cp .env.example .env

Then prepare your database

    ./artsan migrate:fresh --seed

Then run tests

    ./vendor/bin/phpunit
