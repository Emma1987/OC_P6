# OC_P6
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/bf5f7082599148a6ab868d8e5292f374)](https://www.codacy.com/app/Emma1987/OC_P6?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Emma1987/OC_P6&amp;utm_campaign=Badge_Grade)

## Community site for snowboard fans
### A Symfony project created on March 2, 2018, 1:11 pm.

## Getting Started
You can download the project, or clone it with Git by using the green button "Clone or download". You can run it on your local machine for development and testing purposes.

## Prerequisites
PHP 7.0 
MySql 5.6.35 
Apache

## Installing
For installing the project, you have to clone or download it. For running it on your local machine, you can install MAMP (or WAMP for Windows), and put it in the htdocs (or www) file.

1. Execute the command `composer update` to update the dependancies.
2. Execute `php bin/console doctrine:database:create` and `php bin/console doctrine:schema:update --force` to create database and all the entities.
3. Execute `php bin/console doctrine:fixtures:load` to download some tricks.

Now, you can go on http://localhost/ and use the application !

## Built With
Bootstrap 4 - the famous CSS framework
Symfony 3.4 - PHP framework

## Add-ons
Doctrine Fixtures Bundle to load fixtures - https://github.com/doctrine/DoctrineFixturesBundle
PHP Unit to run some tests - https://github.com/sebastianbergmann/phpunit
PHP Code Sniffer to respect PSR 1 & 2 - https://github.com/squizlabs/PHP_CodeSniffer
