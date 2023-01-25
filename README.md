# POC Elasticsearch

## To Install

```bash
$ docker-compose up -d
$ docker-compose exec php /usr/local/bin/entrypoint.sh composer install
$ docker-compose exec php /usr/local/bin/entrypoint.sh bin/console doctrine:migrations:migrate --no-interaction
$ docker-compose exec php /usr/local/bin/entrypoint.sh bin/console doctrine:fixtures:load --no-interaction
$ docker-compose run --rm node yarn install
$ docker-compose run --rm node yarn dev
```

## To open application

Click this [link](https://elastica.docker.localhost/).
