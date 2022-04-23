# LoL-Tracker

## Setup/Tear down

### Drop Database
`php bin/console doctrine:database:drop --force`

### Create Database
`php bin/console doctrine:database:create`

### Create Migrations From Mapping Infos
`php bin/console doctrine:migrations:diff`

### Load Migrations
`php bin/console doctrine:migrations:migrate --no-interaction`