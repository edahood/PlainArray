PlainObject
======

[http://edahood.github.com/plain/](http://edahood.github.com/plain/)

 Magical Base Object Classes. The plain project includes the PlainObject, PlainArray, and PlainCollection classes.

 PlainObject is the Base Class, and it implements Countable and Serializable. PlainObject is useful, but minimal.

 PlainArray is the real Magical Class, it is Very Useful, Extending PlainObject and Implements Iterator, ArrayAccess.

 The Plain Classes are Mostly Objects, but has toArray() capabilities, plus it will Serialize without all the clutter.

 Intended to be Extendable to do just about anything you need.

 Tested on PHP 5.2.0+

Released under a [BSD license](http://en.wikipedia.org/wiki/BSD_licenses).

Features
--------


Changelog
---------

#### 0.1.3 - released 2011-05-08


Philosophy
----------


The [Pareto Principle](http://en.wikipedia.org/wiki/Pareto_principle) states that *roughly 80% of the effects come from 20% of the causes.* In software development terms, this could be translated into something along the lines of *80% of the results come from 20% of the complexity*. In other words, you can get pretty far by being pretty stupid.
   -- Borrowed from idiorm's Readme

**PlainObject is intentionally Simple** It is meant to be extended with more features, which is what PlainArray does.

**PlainArray is meant to be super useful** It is meant to be used as both an Array and an Object. Most of the code goes into implementing the extra interfaces.

**Still Beta Testing** While no major API changes are planned, I would like to trim some fat, so be warned.

Let's See Some Code
-------------------

The first thing you need to know about Idiorm is that *you don't need to define any model classes to use it*. With almost every other ORM, the first thing to do is set up your models and map them to database tables (through configuration variables, XML files or similar). With Idiorm, you can start using the ORM straight away.

### Setup ###

First, `require` the Plain Classes by loading the plain.php:

    require_once 'plain.php';

This will load PlainObject, PlainArray, and PlainCollection



### Usage ###

$p = new PlainArray();
$p->first_name = 'Mike' ;  // Set first_name using magic __set()

// Get a Count
echo count($p) ; // 1

// Get an Actual Array

$res = $p->toArray();
var_dump($res); // array(1) {
                //    ["first_name"]=>
                //    string(4) "Mike"
               //     }



// Access it Like an Array
echo $p[first_name] ; // Mike

// Access it Like an Object
echo $p->first_name ; // Mike

// Access the First Value
echo $p->first() ; // Mike

// Access the Last Value
echo $p->last() ; // Mike


$p['height'] = 82;

// Get a Count
echo count($p) . "\n" ; // 2

// Get an Actual Array

$res = $p->toArray();
var_dump($res); // array(1) {
                //    ["first_name"]=>
                //    string(4) "Mike",
               //     ["height"]=>
               //     int(82)
               //     }

$p->print_r(); // Print The Data
               /* OUTPUT:
                Array
                (
                    [first_name] => Mike
                    [height] => 82
                )

               */


$res  = $p->print_r(true); // Save the print_r result to a string, just like print_r

echo $p->to_json(); // {"first_name":"Mike","height":82}

$keys = $p->keys(); // $keys == array('first_name', 'last_name');

function boolTest($res){
   $res =  $res !== false ? "TRUE" : "FALSE";
   return $res;
}

echo boolTest( isset($p['first_name'] ) ) . "\n" ; // TRUE

echo boolTest( isset($p->first_name ) )  . "\n"; // TRUE

echo boolTest( isset($p->last_name ) )  . "\n"; // FALSE


echo "\n=====\n";
echo boolTest( array_key_exists('last_name',$p ) )  . "\n"; // FALSE


See the test files test/*Test.php for more examples.

##### Warnings and gotchas #####

* is_array() will return false on PlainObject Instances.  This is why there is a static function PlainObject::is_iterable().

* print_r($plain) will probably give you extra output. Instead use $plain->print_r();  The extra output is because it can see all the protected properties.

* current($plain_array) will not work as expected, the current() function is used by the iterator. Instead Use $plain->current();