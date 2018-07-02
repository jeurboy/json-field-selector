<?php

namespace Jeurboy\SimpleObjectConverter;

class DataParser {
    private $_is_debug = false;

    public function isDebug() : bool {
        return $this->_is_debug;
    }

    public function setDebug(bool $bool) {
        $this->_is_debug = $bool;
    }

    public function getOutput($data, ParsedObjectArray $parse_layout) {
        $return = [];
        foreach ($data as $key => $value) {
            if (is_array($data)) {
                if ( $ret = $this->parseApiOutput($data[$key], $parse_layout, $key)) {
                    $return[$key] = $ret;
                }
            }

            if (is_object($data)) {
                if($ret = $this->parseApiOutput($data->{$key}, $parse_layout, $key)) {
                    $return->{$key} = $ret;
                }
            }
        }

        return $return;
    }

    private function parseApiOutput( $all_data, ?ParsedObjectArray $layout, ?string $field_name = '') {
        if( empty($layout) ) return $all_data;

        $return = [];
        if (( is_array($all_data) || is_object($all_data)) ) {
            if ( is_object($all_data) ) {
                if ($this->isDebug()) {
                    print "================= Class Model =================\n";
                    $this->_debug($field_name, $layout, $all_data);
                    print "=================================================\n";
                }

                if($layout->isDefaultDefined($field_name) ) {
                    return $all_data;
                } else {
                    foreach ($layout[$field_name] as $key => $value) {
                        $return[$key] = $this->parseApiOutput($all_data->{$key}, $layout->getParsedObjectChild($field_name), $key);
                    }

                    // Skip record if no field match
                    if(!empty($return)) return $return;
                }

                return;
            }

            // Sequencial array
            else if ( $this->isAssoc($all_data) == false ) {
                foreach ($all_data as $data) {
                    if ($this->isDebug()) {
                        print ">>>>>>>>>>>>>>>> Array >>>>>>>>>>>>>>>>\n";
                        $this->_debug($field_name, $layout, $all_data);
                        print ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>\n";
                    }

                    if(empty($layout->getParsedObjectChild($field_name)) && !$layout->isDefaultDefined($field_name)) { continue; }

                    $return_each_record = $this->parseApiOutput($data,  $layout->getParsedObjectChild($field_name));
                    if(!empty($return_each_record )) $return[] = $return_each_record ;
                }

                if(count($return) > 0)
                    return $return;
            }

            // Hash array
            else {
                foreach ($all_data as $key_name => $data) {
                    if ($this->isDebug()) {
                        print "================= Hash Array =================\n";
                        $this->_debug($field_name, $layout, $all_data);
                        print "=================================================\n";
                    }

                    if(!empty( $layout->getParsedObjectChild($field_name))) {
                        $value = $this->parseApiOutput($data, $layout->getParsedObjectChild($field_name), $key_name);

                        if(!empty($value)) $return[$key_name] = $value;
                    } else if ($layout->isDefaultDefined($field_name)) {
                        $return[$key_name] = $this->parseApiOutput($data, null , $key_name);
                    } else if ($layout->isDefaultDefined($key_name)) {
                        $return[$key_name] = $this->parseApiOutput($data, null , $key_name);
                    }
                }
                return $return;
            }
        }

        // Normal case output
        else {
            // print "================= String =================: ".$field_name." : ".$all_data."\n";

            $this->_debug($field_name, $layout, $all_data);
            if (
                //model have field
                ( !empty($layout) ) &&
                //have filed is in list of filter
                ( $layout->isDefaultDefined($field_name) || $layout->getParsedObjectChild($field_name) )
            ) {
            // String value. Normal case.
                return $all_data;
            }
        }
    }

    private function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    private function _debug(string $field, ?ParsedObjectArray $layout, $data) {
        print  "layout\n";
        print_r($layout);
        print  "\n";

        print  "field_name\n";
        print  $field."\n";

        print  "data\n";
        print_r($data);
        print  "\n";
    }
}