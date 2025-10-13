# Technical informations

- This is a plugin created with the sylius/plugin-skeleton-test-application version 1.13
- It runs on PHP 8.1
- It uses Sylius 1.13
- It uses Symfony 5.4
- The dev application runs on Docker. (cf. the [docker-compose.yml](docker-compose.yml))
- Environment variables are defined in the [.env](.env) file

# Project information

- This plugin offers the ability to create, buy and use gift cards.
- The actual code of the plugin is in the [src](src) folder, but the test application (as in the Symfony app that will use this plugin) 
is located in the [tests/Application](tests/Application) folder.

## Directory Structure

The bundle is configured via the file [MacbimSyliusGiftCardsPlugin.php](src/MacbimSyliusGiftCardsPlugin.php).

| Usage                                     | Folder                                         |
|-------------------------------------------|------------------------------------------------|
| Doctrine entites                          | [Entity](src/Entity)                           |
| Entity Factories                          | [Factory](src/Factory)                         |
| Entity Repositories                       | [Repository](src/Repository)                   |
| Entity Form Types                         | [Form](src/Form)                               |
| Entity Doctrine Mapping Files             | [model](src/Resources/config/doctrine/model)   |
| Order Processors                          | [Processor](src/Processor)                     |
| Plugin Dependency Injection               | [DependencyInjection](src/DependencyInjection) |
| Twig Templates                            | [views](src/Resources/views)                   |
| Translations Files                        | [translations](src/Resources/translations)     |
| Symfony And Sylius Services Configuration | [config](src/Resources/config)                 |

# Useful commands

Useful commands can be found in the [Makefile](Makefile).

For instance:

- If you want to clear the Symfony Cache, run : `make cache-clear`
- If you want to open a shell in the php container, run : `make shell` then type your command.
