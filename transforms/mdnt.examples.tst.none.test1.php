<?php
/**
 * ***
 * Name.......: [MDNT] Example: Debug, Phrase output test
 * Description: This debug example will output a default Phrase entity test object
 *
 * Active.....: Yes
 * ***
 * Please note that you need to change the above 3 items to reflect this local transforms.
 * If you copy/paste from other transforms your might end up with dublicate Name and dublicate
 * Description.
 * ***
 **/

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