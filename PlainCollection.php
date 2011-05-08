<?php

/** PlainCollection
 *
 * @version 0.1.3
 * @todo DOCUMENT PlainCollection Methods and Usage
 */
class PlainCollection extends PlainObject implements IteratorAggregate {

    private $count = 0;

    public function getIterator(){
        return new PlainArray($this->_data);
    }
    public function add($value){
        $klass = "PlainArray";
        if (self::is_array_with_keys($value) ) $value = new $klass($value);
        elseif(is_array($value))
            {
            foreach($value as $k=>$v){if (self::is_array_with_keys($v) ) $value[$k] = new $klass($v);}
           }
        $this->_data[$this->count++] = $value;
    }
    public function first(){
         $cnt = count($this->_data);

        if ($cnt > 0){
        $vals = array_values($this->_data);
            return $vals[0];
        }
        else
            return false;

    }

    public function last(){
        $cnt = count($this->_data);

        if ($cnt > 0){
        $vals = array_values($this->_data);
            return $vals[$cnt-1];
        }
        else
            return false;

    }
    public function items(){
        return $this->_data;
    }

    public function item($index){
        return $this->_data[$index];
    }
    public function getItem($index){return call_user_func_array(array($this, 'item'), array($index));}
    public function remove($index){
        $reIndex = false;
        if (isset($this->_data[$index])){
         if (count($this->_data) > $index -1) $reIndex = true;
         $this->count--;
         $res = clone $this->_data[$index];
         unset($this->_data[$index]);
         if($reIndex === true){
             $arr = array_slice($this->_data, $index-1);
             $this->_data = array_slice($this->_data, 0 , $index-1);
             $this->count = count($this->_data);
             foreach($arr as $curr){
                 $this->_data[$this->count++] = clone $curr;
             }
         }
        }
        return $res;
    }

    public function to_json(){
        $args = func_get_args();
        $res = call_user_func_array(array($this, "toArray"), $args);
        return json_encode($res);

    }

    /** print_r
     *  Replicates the print_r function by calling toArray() on $this
     *  Just like print_r it accepts as the last (only) parameter a boolean telling
     *   it if it should return the result as a string or just echo it
     * @param boolean $as_string [default=false]
     * @return string|void
     */
    public function print_r($as_string=false){
        $arr = $this->toArray();
        if ($as_string === true)
            return print_r($arr, true);
        else
            print_r($arr);
    }
}