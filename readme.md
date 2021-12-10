# KoalityEngine Command Line Interface

This command line interface helps to run API requests for koality.io on the command line. 

**WARNING**: This is the alpha version of the CLI tool for the KoalityEngine used by www.leankoala.com, www.koality.io and platform360.io. Please only use it if the team of koality.io supports you. 

## Install

```shell
composer install
cp .env.example .env
```

Enter your koality.io credentials into `.env`.

## Usage

```shell
php bin/engine.php <command>
```

To get a list of available commands run

```shell
php bin/engine.php list
```

To get help on a command run

```shell
php bin/engine.php <command> -h
```

## Commands

New commands will be added everytime we or a customer needs an easy and automated way to do a koality action from  the command line. If something is missing contact `support@koality.io` or create it on your own.

### Project
- `project:list` list all projects for the given user.
- `project:users` list all users for the given project.

### Incident
- `incident:list` list all incidents of the given project.

### User
- `user:invite` invite a user to an existing project. 
- `user:delete` delete a user.

[More about user commands.](docs/user.md)

### Crawl
- `crawl:project:list` show all crawls for a given project and system.
- `crawl:company:list` show all crawls for a given company.


- `crawl:company:run` run a crawl for a given company.

### Subscription
- `subscription:plan:set` set the subscription plan for the given user (**needs provider rights**).

## Output format

Every command that produces structured output will create a nice ASCII table that is human-readable. As this output is not very handy for further tools to work with it is possible to switch the format to CSV by using the option `-o`.

### Example
```shell
php bin/engine.php project:list -o csv
```

## API

The KoalityEngine follows an API first approach. That means that every that can be done via the koality.io tool can also be done via the API.

- [The PHP API client](https://github.com/leankoala-gmbh/leankoala-client-php)
