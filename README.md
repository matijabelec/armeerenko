# armeerenko
The Battle of 2 Armies Simulator

## Requirements

A requirement to run this project inside of docker is, obviously, `docker-compose` & `docker` locally configured.

## Installation

Start (and pull/build) containers:
`docker-compose up -d`

Install composer packages
`bin/composer install`

App is up and ready to be used at `http://localhost:8080`.

## Details

### Application / Routes

Route definitions can be found inside of `app/routes.php`.

There is `GET /battle` route with parameters `army1` and `army2` which is used as battle simulator.

Other route `GET /` is just "homepage" with title of project.

### Domain (`src/BattleSimulation/`)

Battle is simulated through `src/BattleSimulation/Battle.php` with all possible events that can happen on battle
created as implementation of `src/BattleSimulation/Event/BattleEvent.php`.

Battle is fully random through `\Armeerenko\BattleSimulation\BattleSimulator::simulate()` where three event may happen:
1. Army1 attacks Army2 (attack can be successful or failed),
2. Army2 attacks Army1 (attack can be successful or failed),
3. Random explosion that can hurt one or both armies.

### (Mini) Framework (`framework/`)

There is simple "framework" created for this application/project that simplifies routing.

### Docker

There is no env variables/configuration implemented, just docker containers for web and php-fpm. There are few helper
scripts available inside of `bin/` directory to simplify development.
