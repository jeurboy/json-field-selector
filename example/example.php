<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Jeurboy\SimpleObjectConverter\Parser;

testSimple();
testSimpleWithSubfieldWithSetDefault();
testSimpleWithSubfieldAndNotSetDefault();
testMultiLevel();
testForWard();
testBackWard();

function testSimple(){
    $parser = new Jeurboy\SimpleObjectConverter\Parser();
    $parser->addSchema("test1");
    $token = $parser->getParsedSchema();

    if ($token->isDefaultDefined('test1') !== true ){
        print "Error\n";
    };

}

function testSimpleWithSubfieldWithSetDefault(){
    $parser = new Jeurboy\SimpleObjectConverter\Parser();
    $parser->addSchema("test1,test1.eeee");
    $token = $parser->getParsedSchema();

    if ($token->isDefaultDefined('test1') !== true ){
        print "Error\n";
    };
}

function testSimpleWithSubfieldAndNotSetDefault(){
    $parser = new Jeurboy\SimpleObjectConverter\Parser();
    $parser->addSchema("test1.eeee");
    $token = $parser->getParsedSchema();

    if ($token->isDefaultDefined('test1') === true ){
        print "Error\n";
    };
}

function testMultiLevel(){
    $parser = new Jeurboy\SimpleObjectConverter\Parser();
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
    $parser = new Jeurboy\SimpleObjectConverter\Parser();
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
    $parser = new Jeurboy\SimpleObjectConverter\Parser();
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