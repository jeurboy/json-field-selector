Object field selector for PHP Object
=============================

## Description
The library for filter complex array/hash or object and return the actual fields you want to use. 

If you have too large variable and contain unused value which not necessary to use. The library will help by screen all of unuse field and 

The library can traverse the complex array or nested array in any level of array/hash or object.

---

For a deeper knowledge of how to use this package, follow this index:

- [Installation](#installation)
- [Usage](#usage)


# Installation

You can install the package via `composer require` command:

```shell
composer require jeurboy/object-field-selector
```

Or simply add it to your composer.json dependences and run `composer update`:

```json
"require": {
    "jeurboy/object-field-selector": "*"
}
```
# Usage
```php
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1");
    $token = $parser->getParsedSchema();

    $data = [
        'test1' => "11234"
    ];

    $DataParser = new Jeurboy\SimpleObjectConverter\DataParser();
    $return = $DataParser->getOutput($data, $token);
    print_r($return);
```

You will see following result.

```
Array
(
    [test1] => 11234
)
```
