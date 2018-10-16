<?PHP
/**
 * This is the default include file for all local transforms.
 * -
 * This is just to streamline it, there is no reason why you could not just include all the stuff
 * on your own free time. I'm jsut trying to make it easier for myself...
 */

require_once(dirname(__FILE__) . "/../etc/config.default.php");
if (is_file(dirname(__FILE__) . "/../etc/config.local.php")) require_once(dirname(__FILE__) . "/../etc/config.local.php"); // Let's also activate local settings if there...
$config = array_merge($config, $config_local); // Let's overwrite config with any local changes

// Include our own custom curl functions to fetch data
include_once(dirname(__FILE__) . "/../includes/curl.include.php");

// Include the Maltego Library (Local version)
include_once(dirname(__FILE__) . "/../includes/MaltegoTransform.php");

// Set return content-type to be XML (For when we someday are not Local)
if ( php_sapi_name() != "cli" ) header ("content-type: text/xml");

// Set error level
error_reporting($config["php_errorlevel"]);

// Set PHP Memmory limit
ini_set('memory_limit',$config["memlimit"]);

?>