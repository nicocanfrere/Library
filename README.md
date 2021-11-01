# Library

- [Requirements](#requirements)
- [Install](#install)
- [Docker](#docker)
  - [What's inside?](#whats-inside)
  - [Start Docker](#start-docker)
- [Install database](#install-database)
- [Access application](#access-application)
  - [Routes](#routes)
    - [Books](#books)
    - [Library subscribers aka users](#library-subscribers-aka-users)
- [Run PHPUnit tests](#run-phpunit-tests)
- [Other tools - static analysis](#other-tools---static-analysis)
- [Project directory structure](#project-directory-structure)
- [Enjoy !](#----enjoy----)

## Requirements

- [git](https://git-scm.com/doc)
- [Docker](https://docs.docker.com/) and [Docker Compose](https://docs.docker.com/compose/)
- [PHP 8](https://www.php.net/docs.php)
- [composer](https://getcomposer.org/)
- [Make](https://www.gnu.org/software/make/manual/make.html) to run Makefile (optional)

## Install

Clone the repository, in cli do:
```bash
git clone https://github.com/nicocanfrere/Library.git
```
In project root directory, install vendors with composer, in cli do:
```bash
composer install
```
Rename config/autoload/local.php.dist to config/autoload/local.php

## Docker

### What's inside?

Three containers will run:
- library_database: [postgres:13](https://hub.docker.com/_/postgres)
- library_php: [PHP8](https://hub.docker.com/_/php). Custom image, based on php:8.0.10-fpm (see .local/docker/php/Dockerfile) ***!!! Not safe, do not use in production !!!***
- library_nginx: [nginx:1.19](https://hub.docker.com/_/nginx)

### Start Docker

With make:

```bash
make up
```

Without Make:

```shell
docker-compose -f .docker/docker-compose.yml up
```

## Install database

Next, create the database schema with [Phinx](https://phinx.org/) migrations, in cli do:
```bash
vendor/bin/phinx migrate
```
Now, fill tables with fake data (optional), in cli do:
```bash
vendor/bin/phinx seed:run
```

## Access application

Before you need to rename:
- config/autoload/local.php.dist to config/autoload/local.php
- config/autoload/development.local.php.dist to config/autoload/development.local.php

Access the application in your browser:
```text
http://127.0.0.1:9999
```
Or with Curl request, or if you have PhpStorm, you will find "http" files in .http-requests directory 

### Routes

### Api doc

<pre style="font-size: 16px;">
<code>
- Api doc, JSON format: GET http://127.0.0.1:9999/api/doc
</code>
</pre>

#### Books
<pre style="font-size: 16px;">
<code>
- List all (no pagination...):  GET     http://127.0.0.1:9999/api/library/books
- Get one by uuid:              GET     http://127.0.0.1:9999/api/library/books/&lt;book_uuid&gt;
- Create new book:              POST    http://127.0.0.1:9999/api/library/books
- Partial update book:          PATCH   http://127.0.0.1:9999/api/library/books/&lt;book_uuid&gt;
- Full update book:             PUT     http://127.0.0.1:9999/api/library/books/&lt;book_uuid&gt;
- Delete book:                  DELETE  http://127.0.0.1:9999/api/library/books/&lt;book_uuid&gt;
</code>
</pre>
#### Library subscribers aka users
<pre style="font-size: 16px;">
<code>
- List all (no pagination...):    GET     http://127.0.0.1:9999/api/library/subscribers
- Get one by uuid:                GET     http://127.0.0.1:9999/api/library/subscribers/&lt;subscriber_uuid&gt;
- Create new subscriber:          POST    http://127.0.0.1:9999/api/library/subscribers
- Partial update subscriber:      PATCH   http://127.0.0.1:9999/api/library/subscribers/&lt;subscriber_uuid&gt;
- Full update subscriber:         PUT     http://127.0.0.1:9999/api/library/subscribers/&lt;subscriber_uuid&gt;
- Delete subscriber:              DELETE  http://127.0.0.1:9999/api/library/subscribers/&lt;subscriber_uuid&gt;
- Subscriber borrow books:        POST    http://127.0.0.1:9999/api/library/subscribers/&lt;subscriber_uuid&gt;/books
- Subscriber bring back a book:   DELETE  http://127.0.0.1:9999/api/library/subscribers/&lt;subscriber_uuid&gt;/books/&lt;book_uuid&gt;
</code>
</pre>
## Run PHPUnit tests

With make:

```bash
make punit
```

Without Make:

```shell
vendor/bin/phpunit
```

## Other tools - static analysis

- [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) (make csf or vendor/bin/phpcbf)
- [PHPStan](https://github.com/phpstan/phpstan) (make stan or vendor/bin/phpstan analyse)
- [deptrac](https://github.com/qossmic/deptrac) (make deptrac or vendor/bin/deptrac)

## Project directory structure
<pre style="font-size: 16px;">
Library (root directory)
|
|_ .http-requests (files to run http calls to api)
|   |_ ... files
|
|_ .local (docker local env)
|   |_ docker
|   |_ volumes
|   |_ docker-compose.yml
|
|_ bin
|
|_ config (App configuration)
|   |_ ...files
|
|_ data (cache, ...)
|   |_ ...files
|
|_ db (Phinx migrations and seeds)
|   |_ cache
|   |   |_ ...files
|   |_ logs
|   |   |_ ...files
|   |_ api-doc
|       |_ openapi.json
|
|_ public (Document root directory)
|   |_ index.php (App entry point)
|
|_ src (Application main code base)
|   |_ App (Mezzio App)
|   |_ Infrastructure (Database...)
|   |_ Library (Core)
|
|_ test (Tests directory)
|   |_ ...files
|
|_ vendor (Vendor directory)
|   |_ All packages ...
|
|_ All files for composer, static analysis tools, etc...
</pre>

## !!! *** :-) ENJOY :-) *** !!!
