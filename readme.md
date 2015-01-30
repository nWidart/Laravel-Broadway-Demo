# Laravel Broadway example app

This is an example application using the [Broadway](https://github.com/qandidate-labs/broadway/) package.

It's basically the `examples/` directory in a Laravel application. When I'm feeling more comfortable, there will be more examples.

This demo project uses the [Laravel-Broadway](https://github.com/nWidart/Laravel-broadway) package.

## Requirements

- PHP >=5.5
- Elasticsearch
- MySQL

## Installation

### Clone this repository

```
git clone https://github.com/nWidart/Laravel-Broadway-Demo.git
```

### Configure the database connection information

Edit the `app/config/local/database.php` file to suit your needs.

### Run the migrations

```
php artisan migrate
```

### Run the server

```
php artisan serve
```

## Examples

### [Command Handling](https://github.com/qandidate-labs/broadway/tree/master/examples/command-handling)

* Route: `/command-handling`
* Code: `Modules/Controllers/HomeController@index`

### [Event Dispatcher](https://github.com/qandidate-labs/broadway/tree/master/examples/event-dispatcher)

* Route: `/event-dispatcher`
* Code: `Modules/Controllers/HomeController@eventDispatcher`

### [Event Handling](https://github.com/qandidate-labs/broadway/tree/master/examples/event-handling)

* Route: `/event-handling`
* Code: `Modules/Controllers/HomeController@eventHandling`

### [Event sourced child entity](https://github.com/qandidate-labs/broadway/tree/master/examples/event-sourced-child-entity)

* Route: 
    * Write: `/parts/manufacture`
    * Read: `/parts/manufactured-parts/`
    * Rename: `/parts/rename/uuid/newName`
* Code: `Modules/Parts` 
* Tests: `Tests/Parts/`

### [Event sourced domain](https://github.com/qandidate-labs/broadway/tree/master/examples/event-sourced-domain-with-tests)

* Route: `n/a`
* Code: *coming soon*
* Tests: *coming soon*

### [Serializer](https://github.com/qandidate-labs/broadway/blob/master/examples/serializer/serializer.php)

* Route: *coming soon*
* Code: *coming soon*
