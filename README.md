# Quizzy Backend

![CI](https://github.com/dunglas/symfony-docker/workflows/CI/badge.svg)

Quizzy is a backend project built on a Docker-based infrastructure for the [Symfony](https://symfony.com) web framework, with [FrankenPHP](https://frankenphp.dev) and [Caddy](https://caddyserver.com/) inside. It provides a powerful backend environment to create, manipulate, and respond to quizzes.

## Features

-   Create and manage quizzes
-   Respond to quizzes
-   API documentation available at `/api/doc`

## Getting Started

To run Quizzy, follow these steps:

1. **Environment Setup**: Create a file named `.env.local` and set your database URL:
    ```dotenv
    DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/quizzy?serverVersion=16&charset=utf8"
    ```
2. Use `make start` to launch the Docker environment.
   Database Schema: Connect to the Docker container with make sh and run the following commands:

3. Connect to the Docker container with `make sh` and run the following commands:

```bash
# Create the database schema
php bin/console doctrine:migrations:migrate

# Load fixtures
php bin/console doctrine:fixtures:load
```

## Testing Endpoints

We use [Insomnia](https://insomnia.rest/) to test API endpoints. You can import the Insomnia configuration from the `.insomnia` folder. [Here's how to import in Insomnia](https://docs.insomnia.rest/insomnia/import-export-data).

## Additional Setup

For local development, you can add a TLS certificate to avoid security warnings. Refer to the [Caddy TLS guide](https://caddyserver.com/docs/tls) for more information.

## Stopping the Project

To stop and clean up the Docker environment, use:

```bash
docker compose down --remove-orphans
```

## License

This project is licensed under the MIT License.

## Contributing

Contributions are welcome! Please create issues or submit pull requests on our GitHub repository.

## Contact

If you have any questions or need support, you can reach us through our GitHub repository.

## Additional Resources

1. [Build options](docs/build.md)
2. [Using Symfony Docker with an existing project](docs/existing-project.md)
3. [Support for extra services](docs/extra-services.md)
4. [Deploying in production](docs/production.md)
5. [Debugging with Xdebug](docs/xdebug.md)
6. [TLS Certificates](docs/tls.md)
7. [Using a Makefile](docs/makefile.md)
8. [Troubleshooting](docs/troubleshooting.md)
9. [Updating the template](docs/updating.md)
