# KoalityEngine Command Line Interface

This command line interface helps to run API requests for koality.io on the command line. 

**WARNING**: This is the alpha version of the CLI tool for the KoalityEngine used by www.leankoala.com, www.koality.io and platform360.io. Please only use it if the team of koality.io supports you. 

## Usage

Copy the `.env.example` file to `.env` and enter your koality.io credentials.

```shell
php bin/engine.php <command>
```

## Commands

New commands will be added everytime we or a customer needs an easy and automated way to do a koality action from  the command line. If something is missing contact `support@koality.io` or create it on your own.

- `project:list` show all projects for the given user.

- `user:invite` invite a user to an existing project. [More about user commands.](docs/user.md)

## API

The KoalityEngine follows an API first approach. That means that every that can be done via the koality.io tool can also be done via the API.

- [The PHP API client](https://github.com/leankoala-gmbh/leankoala-client-php)
