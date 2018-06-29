<?php


namespace Jeurboy\SimpleObjectConverter;

class Util {
    /**
     * Recursively merges two objects and returns a resulting object.
     * @param object $obj1 The base object
     * @param object $obj2 The merge object
     * @return object The merged object
     */
    public function mergeObjectsRecursively($obj1, $obj2)
    {
        $merged = $this->_mergeRecursively($obj1, $obj2);
        return $merged;
    }


    public function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Recursively merges two objects and returns a resulting object.
     * @param object $obj1 The base object
     * @param object $obj2 The merge object
     * @return object The merged object
     */
    private function _mergeRecursively($obj1, $obj2) {
        if(empty($obj1))
            return $obj2;

        if (is_object($obj2)) {
            $keys = array_keys(get_object_vars($obj2));

            foreach ($keys as $key) {
                if (
                    isset($obj1->{$key})
                    && is_object($obj1->{$key})
                    && is_object($obj2->{$key})
                ) {
                    $obj1->{$key} = $this->_mergeRecursively($obj1->{$key}, $obj2->{$key});
                } elseif (isset($obj1->{$key})
                    && is_array($obj1->{$key})
                    && is_array($obj2->{$key})
                ) {
                    $obj1->{$key} = $this->_mergeRecursively($obj1->{$key}, $obj2->{$key});
                }
                else if(!empty($obj2->{$key})) {
                        $obj1->{$key} = $obj2->{$key};
                }
            }

        } elseif (is_array($obj2)) {
            if ( is_array($obj1) && is_array($obj2) ) {
                foreach ($obj2 as $key => $value){
                    if (empty($obj1[$key])) {
                        $obj1[$key] = $obj2[$key];
                    }

                    if(!empty($obj2[$key])) {
                            $obj1[$key] = $this->mergeObjectsRecursively($obj1[$key], $obj2[$key]);
                    }
                }
            } else {
                $obj1 = $obj2;
            }
        }

        return $obj1;
    }
}