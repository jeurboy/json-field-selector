Field selector for PHP Object
=============================

## Description
The library for filter complex array/hash or object and return the actual fields you want to use.

If you have a large variables contained with unused values which not necessary to used. The library will helps by filter out all the unused fields and left your fields on return variable.

The library can traverse the complex array or nested array in any level of array/hash or object.

---

For a deeper knowledge of how to use this package, follow this index:

- [Installation](#installation)
- [Usage](#usage)


# Installation

You can install the package via `composer require` command:

```shell
composer require jeurboy/object-field-selector:dev-master
```

Or simply add it to your composer.json dependences and run `composer update`:

```json
"require": {
    "jeurboy/object-field-selector": "dev-master"
}
```
# Usage
For simple usage. Just create returned schema and send to DataParser;
```php
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1");
    $token = $parser->getParsedSchema();

    $data = [
        'test1' => "11234",
        'test2' => "22222"
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

If you have nested Array just pass .(Dot) in your schema.
```php
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1.eee");
    $token = $parser->getParsedSchema();

    $data = [
        'test1' => [
            'eee' => "hello holy",
        ]
    ];

    $DataParser = new Jeurboy\SimpleObjectConverter\DataParser();
    $return = $DataParser->getOutput($data, $token);
    print_r($return);
```

You will get result array like this.
```
Array
(
    [test1] => Array
        (
            [eee] => hello holy
        )
)
```

If there are multiple field inside input array, use comma(,) to define more fields.

```php
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1");
    $token = $parser->getParsedSchema();

    $data = [
        'test1' => [
            'eee' => "hello holy",
            'fff' => "hello holy",
        ]
    ];

    $DataParser = new Jeurboy\SimpleObjectConverter\DataParser();
    $return = $DataParser->getOutput($data, $token);
    print_r($return);
```

You will see the result has multiple field returned.
```
Array
(
    [test1] => Array
        (
            [eee] => hello holy
            [fff] => hello holy
        )

)
```

The library also support object inside sequencial array.
```php
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1.hello");
    $token = $parser->getParsedSchema();

    $data = [
        'test1' => [
            [
                "hello" => "world1",
                "ok"    => "google1",
            ],[
                "hello" => "world2",
                "ok"    => "google2",
            ],
        ]
    ];
    $DataParser = new Jeurboy\SimpleObjectConverter\DataParser();
    $return = $DataParser->getOutput($data, $token);
    print_r($return);
```

Result is

```
Array
(
    [test1] => Array
        (
            [0] => Array
                (
                    [hello] => world1
                )

            [1] => Array
                (
                    [hello] => world2
                )

        )

)
```

You can see all test case in example/example.php files.
