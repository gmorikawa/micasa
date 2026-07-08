# Micasa

A house management system to help a family to manage daily chores.

## Overview

This is a study project to build a application using Laravel framework.

## Getting Started

This project was built using [Composer](https://getcomposer.org/) as a dependency manager.

To install the necessary dependencies to run this project execute `composer install`.

After installing the dependencies use the following command to start the application:

```sh
php artisan serve
```

## Technical Decisions

### Gave up Repository Pattern

At first, I thought about implementing the repository pattern in this project. Because I wanted to leave the Laravel implentation away from the business logic layer. So I created use-cases classes that would receive a repository implementation and I would implement those repository using Eloquent models to interact with the database, thus separating the _business layer_ and _data access layer_.

Although, from prior strugles I had trying to apply some kind of Clean Archicture in Spring Boot, I started to face similar problems in Laravel. First, I would lose a lot of advantages I have using Eloquent models trying to abstract everything was already made for me. For simpler CRUD actions, this wouldn't have so much of an impact. But for more complex features that interacts with many entities and have to keep the action atomic, I would have to create some other kind of over-engineered abstract just to keep the transaction logic away from the _business layer_.

For that reason, and for the sake of my sanity, I decided to go to a simpler path, using the conveniences Laravel already gives to me. I think that trying keep the _business logic_ agnostic to the framework has its advantages as well disavantages. But for opinionated frameworks like Laravel, you would start fighting the framework if you keep like that.
