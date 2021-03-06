
<?php 
// Set the path to global settings 
// the trailing slash from $_SERVER['DOCUMENT_ROOT'] is not consistently present 
//   depending on the server config
$rootPath = $_SERVER['DOCUMENT_ROOT']."/"; // Ensure there is a trailing slash in Doc Root
$rootPath = str_replace ("//", "/", $rootPath); //Now ensure that there are not 2 trailing slashes

$site['config_library'] = $rootPath.'../lib'; // always one dir down from Document Root
$site['config_global_settings'] = $site['config_library'].'/config.php'; // Inside the config_library dir

// get system wide settings 
if((file_exists($site['config_global_settings'])) && (file_exists($site['config_library'])) && (file_exists($site['config_library'].'/MailClass.inc'))) {
    require_once($site['config_global_settings']);
    // Grab the FreakMailer class 
    require_once($site['config_library'].'/MailClass.inc'); 
} else {
    throw(new Exception("Global Mail Configuration Files do not exist"));
}

// Configuration settings for My Site 

// set the default time zone
date_default_timezone_set('America/Los_Angeles');

// override or extend as needed
$site['from_name'] = 'Safari On The River'; // from email name 
$site['from_email'] = 'cvb@fvbe.info'; // from email address 


//echo "site config";

?> 