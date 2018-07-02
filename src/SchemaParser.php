<?php

namespace Jeurboy\SimpleObjectConverter;

class SchemaParser {
    protected $schema;

    public function echo() {
        print "Hello";
    }

    public function addSchema(string $expected_layout) {
        $schema = [];

        // $poa = new ParsedObjectArray();
        $util = new Util();

        foreach(explode(',', $expected_layout) as $field){
            // print "==========================================\n";
            // print $field."\n";
            $parse_field = $this->parseField($field);

            // print_r( $parse_field );
            $schema = $util->mergeObjectsRecursively($schema,  $parse_field);
            // print_r($schema);
            // print "==========================================\n";
        }
        $this->schema = $schema;
    }

    public function getParsedSchema(){
        return $this->schema;
    }

    protected function parseField($field) {
        $return = [];

        if (substr_count($field , '.') == 0) {
            // No more field to parse.
            $poa = new ParsedObjectArray();
            $poa->addParsedObject($field , new ParsedObject('default'));
            return $poa;

            return [$field => new ParsedObject('default')];
        }

        // Still has more sub field.
        list($cur_field, $child_field) = explode('.', $field , 2);

        $po  = new ParsedObject('child', $this->parseField(trim($child_field)));

        $poa = new ParsedObjectArray();
        $poa->addParsedObject(trim($cur_field), $po);

        return $poa;

        $return[trim($cur_field)] = $po;
        return $return;
    }

}

class ParsedObject {
    public $child_field;
    public $default_field;

    public function __construct($type = 'child', $child_field = ''){
        if ($type == 'child'){
            $this->child_field = $child_field;
        } else {
            $this->default_field = new ParseDefaultObject();
        }
    }
}


class ParseDefaultObject {
    // public $child_field = 'true';
}

class ParsedObjectArray {
    public $ArrayOfParsedObject = [] ;

    public function addParsedObject(string $key , ?ParsedObject $p){
        $this->ArrayOfParsedObject[$key] = $p;
    }

    public function getParsedObject(string $key) : ?ParsedObject {
        return @$this->ArrayOfParsedObject[$key];
    }
    public function getParsedObjectChild(string $key) : ?ParsedObjectArray {
        return @$this->getParsedObject($key)->child_field;
    }

    public function isDefaultDefined(string $key) : bool {
        return !empty($this->ArrayOfParsedObject[$key]->default_field) ? true : false;
    }
}