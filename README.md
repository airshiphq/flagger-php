<p align="center">
  <a href="https://airshiphq.com/" target="_blank">
    <img  alt="Airship" src="https://avatars3.githubusercontent.com/u/29476417?s=200&v=4" class="img-responsive">
  </a>
</p>



# Airship PHP

## Requirement
PHP 5.5 or higher

## Prerequisite

This SDK works with the [Airship Microservice](https://github.com/airshiphq/airship-microservice). Please refer to its documentation before proceeding.

### Content
- [01 Installation](#01-installation)
- [02 Key Concepts](#02-key-concepts)
- [03 Configuring Flags](#03-configuring-flags)
- [04 Usage](#04-usage)


## 01 Installation
`php composer.phar require airship/airship-php`

## 02 Key Concepts

In Airship, feature **flags** control traffic to generic objects (called **entities**). The most common type for entities is `User`, but they can also be other things (i.e. `Page`, `Group`, `Team`, `App`, etc.). By default, all entities have the type `User`.

Entities can be represented by dicitionaries or by using the `Entity` class.

## 03 Configuring Flags

To configure Airship, we would need to pass a new Client instance.

```php
require 'vendor/autoload.php';

// Create an instance with an env key
$airship = new Airship\Airship(new Airship\Client\GuzzleClient('<env_key>'));
```

## 04 Usage
```php
if ($airship->flag('bitcoin-pay')->isEnabled(['id' => 5])) {
  // ...
}

// Define your entity
$entity = [
  'type' => 'User', // 'type' starts with a capital letter '[U]ser', '[H]ome', '[C]ar'. If omittied, it will default to 'User'
  'id' => '1234', // 'id' must be a string or integer
  'displayName' => 'ironman@stark.com', // must be a string. If omitted, the SDK will use the same value as 'id' (converted to a string)
];
// or
$entity = new Entity(1234, 'User', 'ironman@stark.com');

// The most compact form can be:
$entity = [
  'id' => 1234
];
// or
$entity = new Entity(1234);

// as this will translate into:
$entity = [
  'type' => 'User',
  'id' => '1234',
  'displayName' => '1234',
];

$airship->flag('bitcoin-pay')->isEnabled($entity); // Does the entity have the feature 'bitcoin-pay'?
$airship->flag('bitcoin-pay')->getTreatment($entity); // Get the treatment associated with the flag
$airship->flag('bitcoin-pay')->isEligible($entity);
// Returns true if the entity can potentially receive the feature via sampling
// or is already receiving the feature.

// Note: It may take up to a minute for entities gated to show up on our web app.
```


## Attributes (for complex targeting)
```php
// Define your entity with an attributes dictionary of key-value pairs.
// Values must be a string, a number, or a boolean. nil values are not accepted.
// For date or datetime string value, use iso8601 format.
$entity = [
  'type' => 'User',
  'id' => '1234',
  'displayName' => 'ironman@stark.com',
  'attributes' => [
    't_shirt_size' => 'M',
    'date_created' => '2018-02-18',
    'time_converted' => '2018-02-20T21:54:00.630815+00:00',
    'owns_property' => true,
    'age' => 39,
  ],
];
// or
$entity = new Entity(
  1234,
  'User',
  'ironman@stark.com',
  [
    't_shirt_size' => 'M',
    'date_created' => '2018-02-18',
    'time_converted' => '2018-02-20T21:54:00.630815+00:00',
    'owns_property' => true,
    'age' => 39,
  ]
);

// Now in app.airshiphq.com, you can target this particular user using its
// attributes
```

## Group (for membership-like cascading behavior)
```php
// An entity can be a member of a group.
// The structure of a group entity is just like that of the base entity.
$entity = [
  'type' => 'User',
  'id' => '1234',
  'displayName' => 'ironman@stark.com',
  'attributes' => [
    't_shirt_size' => 'M',
    'date_created' => '2018-02-18',
    'time_converted' => '2018-02-20T21:54:00.630815+00:00',
    'owns_property' => true,
    'age' => 39,
  ],
  'group' => [
    'type' => 'Club',
    'id' => '5678',
    'displayName' => 'SF Homeowners Club',
    'attributes' => [
      'founded' => '2016-01-01',
      'active' => true,
    ],
  ],
];
// or
$group = new Entity(
  5678,
  'Club',
  'SF Homeowners Club',
  [
    'founded' => '2016-01-01',
    'active' => true,
  ]
);
$user = new Entity(
  1234,
  'User',
  'ironman@stark.com',
  [
    't_shirt_size' => 'M',
    'date_created' => '2018-02-18',
    'time_converted' => '2018-02-20T21:54:00.630815+00:00',
    'owns_property' => true,
    'age' => 39,
  ],
  $group
);

// Inheritance of values `isEnabled`, `getTreatment`, `getPayload`, and `isEligible` works as follows:
// 1. If the group is enabled, but the base entity is not,
//    then the base entity will inherit the values `isEnabled`, `getTreatment`, `getPayload`, and `isEligible` of the group entity.
// 2. If the base entity is explicitly blacklisted, then it will not inherit.
// 3. If the base entity is not given a variation in rule-based variation assignment,
//    but the group is and both are enabled, then the base entity will inherit
//    the variation of the group's.


// You can ask questions about the group directly (use the `is_group` flag):
$entity = [
  'isGroup' => true,
  'type' => 'Club',
  'id' => '5678',
  'displayName' => 'SF Homeowners Club',
  'attributes' => [
    'founded' => '2016-01-01',
    'active' => true,
  ],
];

$airship->flag('bitcoin-pay')->isEnabled($entity);
```

## Contributing

Dependencies are managed using [Composer](https://getcomposer.org/) and the following documentation assumes that
`composer` is installed on the your executable path.

### Code Style

PSR-2 code style is enforced. Check the code style with `composer check-style` and fix it with `composer fix-style`.

### Running Tests

Tests are run through PhpUnit

```
composer test
```
___

# License
 [MIT](/LICENSE)

[![StackShare](https://img.shields.io/badge/tech-stack-0690fa.svg?style=flat)](https://stackshare.io/airship/airship)
