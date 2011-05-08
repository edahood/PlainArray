<?php
 /**
     *
     * PlainObject
     *
     *
     * A One File Base Object Class.  PlainObject is a countable Object with magic __set and __get methods.
     *
     * Mostly an Object, but has toArray() capabilities, plus it will Serialize without all the clutter.
     * Intended to be Extendable to do just about anything you need.
     *
     * BSD Licensed.
     *
     * Copyright (c) 2011, M. Eliot Dahood
     * All rights reserved.
     *
     * Redistribution and use in source and binary forms, with or without
     * modification, are permitted provided that the following conditions are met:
     *
     * * Redistributions of source code must retain the above copyright notice, this
     *   list of conditions and the following disclaimer.
     *
     * * Redistributions in binary form must reproduce the above copyright notice,
     *   this list of conditions and the following disclaimer in the documentation
     *   and/or other materials provided with the distribution.
     *
     * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
     * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
     * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
     * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE
     * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
     * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
     * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
     * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
     * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
     * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
     *
     */

class PlainObject
                 implements
				      Serializable ,
					  Countable
{


    protected $_data = array();


	/** __construct()
	 *  Create a bew PlainObject
	 *  Optionally pass in data to initialize the objects data
	 * @param mixed $data --- array or iterable object to assign as the data
	 */


    public function __construct( $data = array() )
	{
	  if (func_num_args() >0) $this->setData($data) ;
	}

    /** keys() is array_keys
     *  //... so instead of
     *   array_keys($foo);
     *  //..... if $foo is a PLainObject....
     *   $foo->keys();
     *
	 *
	 * @return array
	 *
     * */

    public function keys(){ return array_keys($this->_data); }


    /** fields() is an alias for keys();
     *
     * @return array;
     * */

    public function fields(){ return $this->keys(); }

	/** setData()
	 *  Set a bunch of $field,$value pairs using an (array or Iterator or foreachable )
	 *  By default the current data is unset(reset to a blank array)  but, you can
	 *   Optionaly merge the new data with the current data
	 *
	 * @param mixed $data -- array, or Iterator containing the data to set
	 * @param boolean $merge ---
	 *         When === true -> $data will be merged with what is already there
	 *		   Otherwise $this->_$data will be reset before assigning the new data
	 */

    public function setData($data, $merge=false)
      { // Unless the data should be merged, clear the _data array
        if ($merge !== true) $this->_data = array();
        if(!self::is_iterator($data))
            foreach($data as $key=>&$val)  $this->__set($key, $val);
        else
            foreach($data as $key=>$val)  $this->__set($key, $val);

      }


    /** toArray($fields=array())  Returns a native array, and if you
     *  give the optional parameter an array of field names, it will filter
     *  the array to just those keys
     *
     * @param array fields  // Not listed in method definition but if you pass an arg in,
     *                      // It will assume your passing an array for keys/fields you want back
     * @return array
     *
     */

    public function toArray()
    {  $data = self::to_array($this->_data);
       if (func_num_args() == 0)
		  return $data;
       //implicit else
       $args = func_get_args();
       return array_intersect_key($data, array_flip($args));
    }

    /** as_array() is an alias for toArray()
     *  this is an instance method, not to be confused with the protected static::to_array() function
     *
     * */

    public function as_array()
	{
	  $args = func_get_args();
	  return call_user_func_array(array($this, "toArray"), $args);
    }

	/** is_empty
	 *  Since you can't really use empty($obj) for PlainObjects, you can use $obj->is_empty()
	 *
	 * @return boolean
	 */

    public function is_empty(){ return empty($this->_data);}

	/** to_json
	 *  get a normalized array representation of the object
	 *  by Recursively calling toArray on any value in _data that responds to 'toArray'
	 *  then return the json_encoded final array
	 *
	 * @return string
	 */
    public function to_json(){
        $args = func_get_args();
        $res = call_user_func_array(array($this, "toArray"), $args);
        return json_encode($res);

    }

    /** print_r
     *  Replicates the print_r function by calling toArray() on $this
     *  Just like print_r it accepts as the last (only) parameter a boolean telling
     *   it if it should return the result as a string or just echo it
	 *
     * @param boolean $as_string [default=false]
	 *
     * @return string|void
     */

    public function print_r($as_string=false){
        $arr = $this->toArray();
        if ($as_string === true)
            return print_r($arr, true);
        else
            print_r($arr);
    }



/*** =========== Magical Methods __get, __set,__unset, __isset , serialize, unserialize, count ============
     *
     * If you  question the magic, go see php's documentation of classes and objects
     * There is a section on it called Magic Methods
     *        http://www.php.net/manual/en/language.oop5.magic.php
	 *
	 *  serialize,unserialize and count are not technically "Magical Methods",
     *  but they are Mystical Methods at the very least so I put them here
	 *
     *
     */


     /** count()
	  *   can be used directly $foo->count() ...
	  *        OR
      *   $foo->count() === count($foo)
      *
      * @return int
      *
      * */

    public function count()
	{
        return count($this->_data);
    }

    /** serialize
     *  Returns the serialized version of $this->_data
     *
     * @return string
     */

    public function serialize()
	{
	  return serialize($this->_data);
	}

    /** unserialize
     *  unserializes a serialized version of $this->_data
     *
     * @return void
     */

    public function unserialize($data)
	{
	  $this->setData(unserialize($data));

    }


  /** __get is called when
   *    you try to $plain_object->$name  and $name is not a property of $plain_object
   *    It allows you to give the illusion of having a property named $name , when the $value is hidden
   *
   *
   *
   * @param string $name -- Property Name to "get"
   * @return mixed
   */

  public function __get($name){
        if ($this->__isset($name))
            return $this->_data[$name];
        else
            return null;
    }

   /** __unset
   *  Unset a property using unset($foo->$name)
   *
   *  Called when you to to unset a property like so:
   *  $foo = new PlainObject(array("blah" => 13));
   *  unset($foo->blah);  // Triggers __unset("blah")
   *
   * @param string $name  -- the name of the thing to be unset
   *
   * @return void
   *
   * */

  public function __unset($name)
      {
        if ($this->__isset($name) === true)
                unset($this->_data[$name]);
      }


  /** __isset is called when $foo is a PlainObject and you call isset($foo->name)
   *
   *   Allows you to use isset($foo) on PlainObject instances
   *
   * @param string $name
   * @return boolean
   */

  public function __isset($name) {return isset($this->_data[$name]); }



  /** __set is called when you $name is not a property of $plain_object and
   *    you try to $plain_object->$name = $foo ;
   *    Ensures that the data is stored in the protected _data array, but accessible like a property
   *
   * @param string $name
   * @param mixed $value
   */

  public function __set($name, $value){
       $klass = get_class($this);
	   // If it's already a PlainObject, no conversion needed
       if ($value instanceof self){}

	   // Convert the array into an instance of this same klass

       else if (self::is_array_with_keys($value) )
		 $value = new $klass($value);

	   // If its an actual array, loop through it and sanitize all the values
	   elseif(is_array($value))
            foreach($value as $k=>$v)
                if ( self::is_array_with_keys($v) )
                    $value[$k] = new $klass($v);


       $this->_data[$name] = $value;

     }


 /** =============== Static Methods ======================== **/

	/** self::is_array_with_keys
	 * An attempt to determine if this is an associative array or something acting like one
	 * @todo Determine if this is really needed
	 *
	 * @param mixed $arr
	 * @return boolean
	 */
    public static function is_array_with_keys($arr){

    $result = is_array($arr);
    $canIter = $result;
    if (!$result && is_object($arr)) {$result =  false; }

    if (is_object($arr)){
      $impls = class_implements($arr);
      $mtchs = array_intersect(array('Iterator', 'IteratorAggregate', 'ArrayAccess'), $impls);
      if (count($mtchs) > 0) {
          $canIter = true;
          $result = true;

      }
    }
    if ($canIter === true){foreach($arr as $k=>$v){if (is_int($k)){$result = false;break;}}}
    return $result;
}


    /** is_iterator
	 *  Checks to see if $arr implements Iterator or IteratorAggregate
	 *  If $arr is not an object it returns false.
	 *  Therefore if $arr is an array() , is_iterator($arr) returns FALSE
	 *
	 * @param mixed $arr
	 * @return boolean
	 */
    public static function is_iterator($arr){

    if (!is_object($arr)) return false;
    $mtchs = array_intersect(array('Iterator', 'IteratorAggregate'), class_implements($arr));
    return count($mtchs) >= 1;

}


     /** to_array -- Static Method self::to_array()
	 *  Convert Iterable or PlainObject Instances to an actual array
	 *  Used by toArray to allow for easy recursive sanitizations of the data
	 *
	 *  Protected method so you can't PlainObject::to_array()
	 *
	 * @param mixed $obj -- The object to convert to an actual array
	 *
	 * @return array
	 *
	 */

    protected static function to_array($obj)
    {
        $out = array();

        foreach ($obj as $key => $value){
            if (self::is_array_with_keys($value) || is_array($value))
			    $value = self::to_array($value);

            $out[$key] = $value;
        }

        return $out;
    }





  /* ================= PlaceHolder Functions ================================ */


    /** save() is something for extending classes to decide or not decide to implement
     *  Intended to be used to Save an Object to a DataSource like a SQL Database, or a Document Store
	 *
	 *
	 * @param mixed $data
     */
    public function save($data=null)
    {
        // Header for extended classes to implement

        return true;

    }

}
