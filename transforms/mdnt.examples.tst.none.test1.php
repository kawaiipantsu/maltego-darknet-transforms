<?php
/**
 * mdnt.examples.tst.none.test1
 * 
 * This will just return a Phrase and dump the $argv to log window.
 * 
 */

require(dirname(__FILE__) . "/../includes/default.inc.php");

// Initiate the Maltego Local Transform Class
$mt = new MaltegoTransform();

// Throw a debug message :)
$mt->debug("Starting Transform");

// Let's do something ...
$NewEnt = $mt->addEntity("Phrase","Test1");
$NewEnt = $mt->addUIMessage(print_r($argv,true));


// Send the output to Maltego
$mt->returnOutput();

?>