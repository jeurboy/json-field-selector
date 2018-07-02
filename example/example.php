<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Jeurboy\SimpleObjectConverter\SchemaParser;
use Jeurboy\SimpleObjectConverter\DataParser;

// testSimple();
// testSimpleWithSubfieldWithSetDefault();
// testSimpleWithSubfieldAndNotSetDefault();
// testMultiLevel();
// testForWard();
// testBackWard();

// testGetOutput();
// testGetOutput2();
// testGetOutput3();
// testGetOutput4();
// testGetOutput5();
// testGetOutput6();
testGetOutput7();

function testGetOutput(){
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1");
    $token = $parser->getParsedSchema();

    $data = [
        'test1' => "11234"
    ];
    print "================= Input Model =================\n";
    print_r( $data);
    print_r( $token);

    $DataParser = new Jeurboy\SimpleObjectConverter\DataParser();
    $return = $DataParser->getOutput($data, $token);
    print_r($return);
}

function testGetOutput2(){
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1.eee");
    $token = $parser->getParsedSchema();

    $data = [
        'test1' => [
            'eee' => "hello holy",
        ]
    ];
    print "================= Input Model =================\n";
    print_r( $data);
    print_r( $token);

    $DataParser = new Jeurboy\SimpleObjectConverter\DataParser();
    $return = $DataParser->getOutput($data, $token);
    print_r($return);
}
function testGetOutput3(){
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1");
    $token = $parser->getParsedSchema();

    $data = [
        'test1' => [
            'eee' => "hello holy",
            'fff' => "hello holy",
        ]
    ];
    print "================= Input Model =================\n";
    print_r( $data);
    print_r( $token);

    $DataParser = new Jeurboy\SimpleObjectConverter\DataParser();
    $return = $DataParser->getOutput($data, $token);
    print_r($return);
}
function testGetOutput4(){
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1.eee");
    $token = $parser->getParsedSchema();

    $data = [
        'test1' => [
            'eee' => [
                "hello" => "world",
                "ok"    => "google",
            ],
            'fff' => "hello holy", // Not see
        ]
    ];
    print "================= Input Model =================\n";
    print_r( $data);
    print_r( $token);

    $DataParser = new Jeurboy\SimpleObjectConverter\DataParser();
    $return = $DataParser->getOutput($data, $token);
    print_r($return);
}

function testGetOutput5(){
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
    print "================= Input Model =================\n";
    print_r( $data);
    print_r( $token);

    $DataParser = new Jeurboy\SimpleObjectConverter\DataParser();
    $return = $DataParser->getOutput($data, $token);
    print_r($return);
}
function testGetOutput6(){
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1.hello.world,test1.hello.siri,test1.test2.ok");
    $token = $parser->getParsedSchema();

    $data = [
        'test1' => [

            "hello" => [
                "world" => "is_not enough",
                "siri"  => "is enough",
            ],
            "ok"    => "google1",

            'test2' => [
                "hello" => "world2",
                "ok"    => "google2",
            ]
        ],
    ];
    print "================= Input Model =================\n";
    print_r( $data);
    print_r( $token);

    $DataParser = new Jeurboy\SimpleObjectConverter\DataParser();
    $return = $DataParser->getOutput($data, $token);
    print_r($return);
}
function testGetOutput7(){
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1.hello.siri.nothave");
    $token = $parser->getParsedSchema();

    $data = [
        'test1' => [

            "hello" => [
                ["world" => "is_not enough"],
                "siri"  => [
                    "from" => [ "steve_job", "wozniak" ]
                ]
            ],
            "ok"    => "google1",

            'test2' => [
                "hello" => "world2",
                "ok"    => "google2",
            ]
        ],
    ];
    print "================= Input Model =================\n";
    print_r( $data);
    print_r( $token);

    $DataParser = new Jeurboy\SimpleObjectConverter\DataParser();
    $return = $DataParser->getOutput($data, $token);
    print_r($return);
}

function testSimple(){
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1");
    $token = $parser->getParsedSchema();

    if ($token->isDefaultDefined('test1') !== true ){
        print "Error\n";
    };

}

function testSimpleWithSubfieldWithSetDefault(){
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1,test1.eeee");
    $token = $parser->getParsedSchema();

    if ($token->isDefaultDefined('test1') !== true ){
        print "Error\n";
    };
}

function testSimpleWithSubfieldAndNotSetDefault(){
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1.eeee");
    $token = $parser->getParsedSchema();

    if ($token->isDefaultDefined('test1') === true ){
        print "Error\n";
    };
}

function testMultiLevel(){
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1.eee.test1.fff.gg");
    $token = $parser->getParsedSchema();

    if ($token->getParsedObjectChild('test1')
            ->getParsedObjectChild('eee')
            ->getParsedObjectChild('test1')
            ->getParsedObjectChild('fff')
            ->isDefaultDefined('gg') !== true ){
        print "Error\n";
    };
}
function testForWard() {
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test1,test1.0,test1.asdf,test1.Ok.test,test1.Ok");

    $token = $parser->getParsedSchema();

    if ($token->isDefaultDefined('test1') !== true ){
        print "Error\n";
    };

    if ($token->getParsedObjectChild('test1')->isDefaultDefined('asdf') !== true ){
        print "Error\n";
    };

}

function testBackWard() {
    $parser = new Jeurboy\SimpleObjectConverter\SchemaParser();
    $parser->addSchema("test2.Ok.haha,test2.Ok,test2");

    $token = $parser->getParsedSchema();

    // print_r($token);
    if ($token->isDefaultDefined('test2') !== true ){
        print "Error\n";
    };
    if ($token->getParsedObjectChild('test2')->isDefaultDefined('Ok') !== true ){
        print "Error\n";
    };
    if ($token->getParsedObjectChild('test2')->getParsedObjectChild('Ok')->isDefaultDefined('haha') !== true ){
        print "Error\n";
    };
    if ($token->getParsedObjectChild('test2')->getParsedObjectChild('Ok')->isDefaultDefined('hadd') === true ){
        print "Error\n";
    };
}