<?php
/**
 * @name bootstrap.php
 * Bootstrap Loader For the PHPUnit Tests of the PlainObject
 *
 * @author M. Eliot Dahood
 */

if(!defined('SLASH')) define('SLASH', DIRECTORY_SEPARATOR);

ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.dirname(__FILE__).'/..');
require_once("plain.php");
?>