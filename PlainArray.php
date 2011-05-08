<?php

/**
     *
     * PlainArray
     * @version 0.1.3
     *
     * A Super Array Class, extends PlainObject to add ArrayAccess, Iterator interfaces.
     *
     *
     * An Object that masquerades as Both an Array and an Object, foreachable, iterable, and awesome.
     * Intended to be used as a replacement for arrays and StdClass/Object.
     *
     *
	 * Example:
	 * $foo = new PlainArray();
	 * $foo->name = "eliot";
	 * $foo['age'] = 29;
	 * $foo->score = 2843;
	 * echo "Count of Foo is: " . count($foo) ; // Will output "Count of Foo is: 3";
	 *
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

class PlainArray
                  extends PlainObject

                  Implements
							Iterator,
							ArrayAccess

{



   /** first
    * Get the first Value from the data array.
    * Returns null if no data present
    *
    * @return mixed
    */

   public function first(){
       if (count($this) > 0){
           $kys = $this->keys();
           return $this->_data[current($kys)];
       }
       else
           return null;
   }

    /** last
    * Get the last value from the data array
    * Returns null if no data present
	*
	*
    * @return mixed
    */

   public function last(){
       if (count($this) > 0){
           $kys = $this->keys();
           return $this->_data[end($kys)];
       }
       else
           return null;
   }



   /*   ============ Iterator Functions ================
     *
     * rewind, current, key, next, valid
     *
     * Makes a "Record" ForEach-able
     * See PHP Docs for info.  Not documenting here
     *
     *
     */

   public function rewind()
   {
      $var = reset($this->_data);
   }

   public function current()
    {
        $var = current($this->_data);
        return $var;
    }

   public function next()
    {
       $var = next($this->_data);
       return $var;
    }


   public function valid()
    {
        $key = $this->key();
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }
   public function key()
    {
        $var = key($this->_data);

       return $var;
    }

   /* ================= ArrayAccess Methods ===================   */

   /** offsetUnset
   * Unset a value like it was a value in an array
   *
   * @param string|int $index  -- Index (Key) to unset
   * @return void
   *
   *
   */

  public function offsetUnset($index)
	{
	  $this->__unset($index);
	}


  /** offsetset($index, $value)  this is called when you do
  *
  * $foo["blah"] = $something_else; // $index = "blah", $value = $something_else
  *
  * @param string|int $index --- Key To set
  * @param mixed $value -- Value to Assign
  *
  * @return void
  */

   public function offsetSet($index, $value)
     {  if (is_null($index))
	       $index = count($this->_data);
        if($this->__isset($index))
           $this->__unset($index);
        $this->__set($index,$value);

    }


  /**
   * offsetGet is an ArrayAccess method that allows for array like behavior.
   * Example:
   * // data Array
   * $data = array("name" => "Joe Bob", "age" => 22, "IQ" => 13);
   *
   * // Create PlainObject from the data array
   * $plain_object = new PlainObject($data);
   *
   * // Create PlainArray from the data array
   * $plain_array = new PlainArray($data);
   *
   *
   * //Access the name "property":
   *
   * // Using the $data array
   * echo sprintf('$data["name"] is: %s', $data["name"]);
   *
   * // Using the PlainArray $plain_array works excatly like an array
   *
   * echo sprintf('$plain_array["name"] is: %s', $plain_array["name"]); // PlainArray Implements ArrayAccess
   *
   * // Using the PlainObject will not work
   *     // This WON'T work
   * This WON'T work echo sprintf('$plain_object["name"] is: %s', $plain_object["name"]);  // This WON'T work
   *
   *
   * @param mixed $index -- Key/Index to Get the value for
   *
   * @return mixed
   *
   */

   public function offsetGet($index) {
       return $this->__get($index);

   }


   /** offsetExists is an array_key_exists kinda deal
    * called when isset($foo[$index])
    *
    *
    * @param string|int $index  //Could be a Key or an index number
    *
    * @return boolean
    * */

   public function offsetExists($index)
   {
       return $this->__isset($index);

   }




   /* ======= MAGIC METHODS ========= */

   /** __clone
    *
    *  Called after Cloning a Instance of PlainArray.  Makes sure that it is recursively Cloning
    *
    * @return void
    *
    */


   public function __clone() {
        foreach ($this->_data as $key => $value) if ($value instanceof self) $this[$key] = clone $value;
    }




   /** =============== Random Functions ============================= **/


   /** generic_func
    * @todo DEPRECATE
    *
    * @param type $name
    * @return type
    */
   protected function generic_func($name){
        $otherargs = array($this->_data);
        $var = call_user_func_array($name, $otherargs);
        return $var;
    }


}