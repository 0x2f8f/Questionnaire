# Questionnaire

## Setup
1. `make build`
2. `make up`
3. `make php`
4. `cd src`
5. `php bin/console doctrine:migration:migrate -n`
6. `php bin/console doctrine:fixtures:load -n`

## Run Questionare
1. `make php` 
2. `cd src && php bin/console app:questionnaire`