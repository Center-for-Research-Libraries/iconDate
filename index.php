<?php
		//setcookie(name, value, expire, path, domain);
	setcookie("emailAddress", "", time()+60*60*24, "/", "192.168.1.195");
	/*
		cookies = part of the http header:
			setcookie() must be called before output to browser

		if (isset($_COOKIE['emailAddress'])) echo "Cookie is " . $_COOKIE['emailAddress'];
		else echo "Cookie sadly not set";		*/

		/*PHP manual:
			"Many proxies and clients can be forced to disable caching with" ...*/
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

	header("Content-type: text/html; charset=utf-8");
	header("Accept-Charset: utf-8");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--the php header statement at top of file is really what forces it to read as UTF-8-->

<?php
		//server-side scripts incl. connection to database
	require_once('config.php'); //also has javascript for cookies
	echo $holdingsStylesLink; //in serverVariables.php: IP address always changing
?>

<title>iconDate: enter issues for ICON project</title>

</head>
<body>

<?php

	include("header.php");
	include("navigationHeader.php");
	include("userTracking.php");
	if ($bShowDebug) { // in debugSetup.php
		echo $strAppDebug . "<hr/>"; //config.php
	}
	if ($str_issue_id) { //we have an issue_id parameter submitted
		include("existingRecordData.php");
		include("setNewIssueData.php"); //contains the input form, and includes warningAlreadyProcessed.php
		include "dateInstructions.php"; //contains info about date formats they may encounter
	} //end if
?>

<a name="readMe"></a>
<div id="readMe" class="documentationReports">

<div class="pageHeader">
	<span class="highlightCode">iconDate</span><br/> is the system to submit starting and ending dates
	from Library of Congress Chronicling America project data
	(in issues.rawIssueData) to mySQL stored procedure which will create individual records for the issues table.
</div>
	<ul>
		<li>Workers use 1 of 3 user names; there are 3 different starting positions in the list:
		<ol>
			<li>user0:  <!-- user0starts, user0ended etc are filled by doc.ready function in userTracking.js -->
				<span id="user0starts">&nbsp;</span>
				<span id="user0ended" class="unimportant">last issue not saved on this machine</span>
			</li>
			<li>user1:
				<span id="user1starts">&nbsp;</span>
				<span id="user1ended" class="unimportant">last issue not saved on this machine</span>
			</li>
			<li>user2:
				<span id="user2starts">&nbsp;</span>
				<span id="user2ended" class="unimportant">last issue not saved on this machine</span>
			</li>
		</ol>
		</li>
		<li>
			At the end of the day,
			<span class="important"> bookmark the form page where you want to start for the next day</span>, <br/>
			or you may have to scroll through thousands of pages to get back.
		</li>
		<li>
			Use the same browser (Firefox) so the next person to act as that user doesn't lose the bookmark.<br/>
		</li>
		<li>
			One machine should be used for one user (user0, user1, user2).
		</li>
	</ul>
</div><!--#readMe-->

<?php
include("footerCRL.html");
?>

</body>
</html>

<?php require_once('dbCleanup.php'); ?>

