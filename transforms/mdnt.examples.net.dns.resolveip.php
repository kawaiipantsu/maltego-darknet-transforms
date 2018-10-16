<?php
/**
 * mdnt.examples.net.dns.resolveip
 * 
 * This will just resovle an IP address into a DNS name.
 * 
 */

require(dirname(__FILE__) . "/../includes/default.inc.php");

// Initiate the Maltego Local Transform Class
$mt = new MaltegoTransform();

// Throw a debug message :)
$mt->debug("Starting Transform");

// Let's get input if it's there!
$ip = trim($argv[1]); // The entity data is located here
$mt->debug("Looking up DNS for IP: $ip");

// Let's do something ...
if (filter_var($ip, FILTER_VALIDATE_IP)) {
    $NewEnt = $mt->addEntity("DNSName",gethostbyaddr($ip));
} else {
    $mt->debug("Input was not an IP address");
}






// Send the output to Maltego
$mt->returnOutput();

?>