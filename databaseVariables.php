<?php

// Set the database access information as constants.
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', '8zaDannych');
DEFINE ('DB_NAME', 'icondata'); //original test database

// Make the connection.
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die ('<h3 class="alert">Could not connect to MySQL: <br /><span class="highlightCode">' . mysqli_error($mysqli) . "</span>. </h3>" );

// Select the database.
mysqli_select_db($mysqli, DB_NAME) or die ('<h3>Could not select the database: <br /><em>' . mysqli_error($mysqli) . "</em></h3>" );
//create query vars
mysqli_query($mysqli, "SET character_set_client=utf8");
mysqli_query($mysqli, "SET character_set_connection=utf8");



	//set database file name, table choice in that database, + search data: may be submitted in a form or query string
$strAppDebug .= "<br/>DB_NAME = '<strong>";
if (isset($_REQUEST["DB_NAME"])) 	{
	$DB_NAME = urldecode($_REQUEST["DB_NAME"]);
	$strAppDebug .= $DB_NAME . "</strong>' from IF block. ";
} else 	{
	$DB_NAME 	= "icondata"; //original test database
	$strAppDebug .= $DB_NAME . "</strong>' ELSE block default. ";
}

	//http://us.php.net/manual/en/mysqli.quickstart.stored-procedures.php
$strAppDebug .= "<br/>storedProcedureName = '<strong>";
if (isset($_REQUEST["storedProcedureName"])) 	{ // used in SQL statements
	$storedProcedureName = urldecode($_REQUEST["storedProcedureName"]);
	$strAppDebug .= $storedProcedureName . "</strong>' from IF block. ";
} else 	{
	$storedProcedureName 	= "INVALID storedProcedureName (value not sent in form data)";
	$strAppDebug .= $storedProcedureName . "</strong>' ELSE block default. ";
}

$strAppDebug .= "<br/>tableChoice = '<strong>";
if (isset($_REQUEST["tableChoice"])) 	{ // database table name used in SQL statements
	$tableChoice = urldecode($_REQUEST["tableChoice"]);
	$strAppDebug .= $tableChoice . "</strong>' from IF block. ";
} else 	{
	$tableChoice 	= "issuesBUFFER";
	$strAppDebug .= $tableChoice . "</strong>' ELSE block default. ";
}

$strAppDebug .= "<br/>issue_id = '<strong>";
if (isset($_REQUEST["issue_id"])) 	{ // used in SQL statements
	$issue_id = urldecode($_REQUEST["issue_id"]);
	$strAppDebug .= $issue_id . "</strong>' from IF block. ";
} else 	{
	$issue_id 	= "0";
	$strAppDebug .= $issue_id . "</strong>' INVALID issue_id (value not sent in form data) ELSE block default. ";
}
$new_issue_id = -1; //invalid value for what should be an INT returned by storedProcedureName

/*
	first_date and last_date are form inputs, type TEXT; each with a single value (we're basically throwing these away)
	startDates and endDates are form inputs, type HIDDEN;
		each is concatenation of strings input by user into first_date and last_date fields,
		separated by pipe character (|) which we use to explode() the submitted form fields into arrays
*/
$strAppDebug .= "; first_date = '<strong>";
if (isset($_REQUEST["first_date"])) 	{ // used in SQL statements
	$first_date = urldecode($_REQUEST["first_date"]); $strAppDebug .= $first_date . "</strong>' from IF block. ";
} else 	{
	$first_date 	= "INVALID first_date (value not sent in form data) "; $strAppDebug .= $first_date . "</strong>' ELSE block default. ";
}
$strAppDebug .= "; last_date = '<strong>";
if (isset($_REQUEST["last_date"])) 	{ // used in SQL statements
	$last_date = urldecode($_REQUEST["last_date"]);
	$strAppDebug .= $last_date . "</strong>' from IF block. ";
} else 	{
	$last_date 	= "INVALID last_date (value not sent in form data)";
	$strAppDebug .= $last_date . "</strong>' ELSE block default. ";
}
$strAppDebug .= ";<br/>startDates = '<strong>";
if (isset($_REQUEST["startDates"])) 	{ // used in SQL statements
	$startDates = urldecode($_REQUEST["startDates"]);
	$startDates = explode("|", $startDates);
	foreach ($startDates as &$x) {
			$x = trim($x);
	}
	$strAppDebug .= $startDates[0] . "</strong>' is 1st element and '<strong>" . $startDates[count($startDates)-1] . "</strong>' is last, hit IF block. ";
} else 	{
	$first_date 	= "INVALID startDates (value not sent in form data) "; $strAppDebug .= $first_date . "</strong>' ELSE block default. ";
}
$strAppDebug .= "; endDates = '<strong>";
if (isset($_REQUEST["endDates"])) 	{ // used in SQL statements
	$endDates = urldecode($_REQUEST["endDates"]);
	$endDates = explode("|", $endDates);
	foreach ($endDates as &$x) {
			$x = trim($x);
	}
	$strAppDebug .= $endDates[0] . "</strong>' is 1st element and '<strong>" . $endDates[count($endDates)-1] . "</strong>' is last, hit IF block. ";
} else 	{
	$endDates 	= "INVALID endDates (value not sent in form data) "; $strAppDebug .= $first_date . "</strong>' ELSE block default. ";
}



/**************************************************************************
//FOR THIS APPLICATION, ISSUES IS THE PRIMARY TABLE, SO RESERVE THIS BLOCK FOR SITUATIONS WHERE PUBLICATIONS IS PRIMARY
$strAppDebug .= "<p>pub_id = '<strong>";
if (isset($_REQUEST["pub_id"])) 	{ // used in SQL statements
	$pub_id = urldecode($_REQUEST["pub_id"]);
	$strAppDebug .= $pub_id . "</strong>' from IF block</p>";
} else 	{
	$pub_id 	= "pub_id not set yet, will be pulled from issue record and set in fillStringVars (must call fillStringVars('issues') first...) ";
	$strAppDebug .= $pub_id . "</strong>' from ELSE block</p>";
}
**************************************************************************/


//global vars corresponding to fields in database tables, prefixed by 'str_'
//ISSUES TABLE
$str_issue_id 		 = $issue_id;
$str_ISSUE_pub_id  = "pub_id from ISSUES"; //separate copy for verification: str_pub_id refers to the one in publications
$str_issue_date = "0000-00-00";									$str_rawIssueData = "rawIssueData";
$str_repos_id			= "repos_id";									$str_condition_id = "conditions key";
$str_format				= "issue format as string";		$str_format_id		= "formats key";
$str_archive_status_id = "archive_status key";	$str_provenance		= "provenance not used yet";
$str_update_date	= "0000-00-00";								$str_issue_note		= "issuesBUFFER.issue_note";
$str_specificIssuesCreated			= 0;						$str_specificIssuesCreatedDate	= "0000-00-00";
//end fields from issues, now PUBLICATIONS TABLE
$str_pub_id 						= "publication unique id";	//filled when issues or publications query made: see functions.php > fillStringVars(datasource)
$str_marc001						= "marc001";
$str_pub_title					= "title";									$str_pub_title_alt			= "title alt";
$str_pub_city						= "city"; 					$str_pub_state	= "state/province";		$str_pub_country	= "country";
$str_pub_bgnDate 				= "1st date";								$str_pub_endDate 				= "Last date";
$str_date260C 					= "date260C";								$str_date362 						= "date362";
$str_pub_freq_id_code		= "freq_id_code";						$str_freq310 			= "frequency310";
$str_fmrFreq321 = "formerFreq321";					$str_pub_freq_code321   = ""; //DOES NOT EXIST IN DATABASE: see further uses here + in form page
$str_numberingNote515 	= "numberingNote515";				$str_summary520 				= "summary520";
$str_descriptionNote588	= "descriptionNote588";
//end fields from publications, now FREQUENCIES TABLE

//SQL queries can be composed in the individual files


?>