<?php
if(!defined('SLASH')) {define('SLASH', DIRECTORY_SEPARATOR);}
foreach(array('Object','Array','Collection') as $c) require_once(dirname(__FILE__).SLASH."Plain".$c.".php");
