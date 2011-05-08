<?php

require_once 'bootstrap.php';

/**
 * Test class for PlainObject.
 * Generated by PHPUnit on 2011-05-06 at 21:37:12.
 */
class PlainObjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PlainObject
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new PlainObject;
    }
    protected function factory(){
        $this->object = new PlainObject(self::factory_array());
        return $this->object;
    }
    protected static function factory_array(){
        return array("name" => "Tom", "age" => 25, "weight" => 170.5, "is_active" => true);
    }
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }


    public function testKeys()
    {
       $this->factory();

       $kys = $this->object->keys();
       $this->assertEquals(array("name","age","weight","is_active"),$kys);

    }


    public function testSetDataMerge()
    {

     $this->factory();
     $ndata = array("weight" => 113.13, "sex" => "M", "style" => "bigfoot","people" => array("ron","paul"));

     $this->object->setData($ndata, true);
     $kys = $this->object->keys();
     $this->assertEquals(array("name","age","weight","is_active","sex","style","people"),$kys);
    }
    public function testSetData()
    {

     $this->factory();
     $ndata = array("weight" => 113.13, "sex" => "M", "style" => "bigfoot", "people" => array("ron","paul"));

     $this->object->setData($ndata, false);
     $kys = $this->object->keys();
     $this->assertEquals(array("weight","sex","style","people"),$kys);
    }

    /**
     * @todo Implement test__get().
     */
    public function test__get()
    {
        $this->factory();
        $this->assertEquals("Tom", $this->object->name );
    }

    public function test__unset()
    {

      $this->factory();
      unset($this->object->name);
      $kys = $this->object->keys();
      $this->assertNotContains("name", $kys);
    }


    public function test__isset()
    {
         $this->factory();
         $this->assertTrue(isset($this->object->name));
         $this->assertFalse(isset($this->object->NotSetMan));
    }


    public function test__set()
    {
        $nm = "Mike";
        $this->object->myname = $nm;
        $this->assertEquals($nm, $this->object->myname);
    }


    public function testSave()
    {
        $this->assertTrue(is_callable(array($this->object, "save")));
    }


    public function testToArray()
    {
        $arr = self::factory_array();
        $this->factory();

        $this->assertEquals($arr, $this->object->toArray());

    }

    public function testAs_array()
    {
        $arr = self::factory_array();
        $this->factory();
        $this->assertEquals($arr, $this->object->as_array());
    }

    public function testIsEmpty(){
        $this->assertTrue($this->object->is_empty());
        $this->factory();
        $this->assertFalse(empty($this->object));
    }
    public function testSerialize(){
        $this->factory();
        $arr = self::factory_array();
        $ser = serialize($this->object);
       // echo "\n" . $ser . "\n";
        $sample = 'C:11:"PlainObject":83:{a:4:{s:4:"name";s:3:"Tom";s:3:"age";i:25;s:6:"weight";d:170.5;s:9:"is_active";b:1;}}';
        $this->assertEquals($sample, $ser);
    }
    public function testUnSerialize(){
        $this->factory();
        $arr = self::factory_array();
        $ser = serialize($this->object);
        $object2 = unserialize($ser);
        $this->assertEquals($arr, $object2->toArray());
    }
    public function testCloning(){
        $this->factory();
        $obj2 = clone $this->object;
        $this->assertNotSame($this->object, $obj2, "Cloned Object should not be the same object");

        $obj3 = $this->object;
        $this->assertSame($this->object, $obj3, "Non-Cloned Object should be the same object");

    }
    public function testTo_json(){
        $this->factory();
        $arr = self::factory_array();
        $tj = json_encode($arr);
        $this->assertEquals($tj, $this->object->to_json());

    }
    public function testIs_array_with_keys()
    {
        $arr = self::factory_array();
        $this->assertTrue(PlainObject::is_array_with_keys($arr));
        $arr2 = array("Goo","BAR");
        $this->assertFalse(PlainObject::is_array_with_keys($arr2));
        $this->factory();
        $this->assertFalse(PlainObject::is_array_with_keys($this->object), "A PlainObject is an array with keys");

		
    }
}
?>