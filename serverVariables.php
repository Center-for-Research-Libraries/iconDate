<?php


/**************************************************************************************
	VARS FOR FILE SYSTEM */

$thisServerIP = $_SERVER['SERVER_ADDR'];
$thisUrl 		  = $_SERVER['PHP_SELF']; // '/iconDate/index.php' or similar
$parts 				= explode("/", $thisUrl);
$partsSize 		= count($parts); //returns array length
$thisFile			= $parts[ $partsSize - 1];

	$holdingsStylesLink = '<link rel="stylesheet" type="text/css" href="index.css" />';
		//next adds the icon
	$holdingsStylesLink .= '<link rel="shortcut icon" href="http://www.crl.edu/sites/default/themes/crl/favicon.ico" type="image/x-icon" />';


$strAppDebug .= "<hr/>file system vars: thisServerIP = '<strong>" . $thisServerIP . "</strong>'; ";
	//$strAppDebug .= "holdingsStylesLink = '<strong>" . $holdingsStylesLink . "</strong>'; ";
	$strAppDebug .= "thisUrl = '<strong>" . $thisUrl . "</strong>'; ";
	$strAppDebug .= "thisFile = '<strong>" . $thisFile . "</strong>'; <hr/>";
/* end VARS FOR FILE SYSTEM
**************************************************************************************/

?>