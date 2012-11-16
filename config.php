<?php
		/*PHP manual:
			"Many proxies and clients can be forced to disable caching with" ...*/
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

	header("Content-type: text/html; charset=utf-8");
	header("Accept-Charset: utf-8");


require_once('debugSetup.php');
require_once('databaseVariables.php');
require_once('serverVariables.php');
require_once('functions.php');
require_once('jquery-1.7.1.min.js');
require_once('navigationJavaScripts.php'); //AJE scripts to figure prev and next page links
require_once('clientSide_pub_ids.js'); // AJE see file for comments
require_once('clientSide_issue_ids.js'); // AJE see file for comments
require_once('date.js'); // date library http://www.datejs.com/2007/11/27/getting-started-with-datejs/ -->
require_once('dateScripts.js'); // AJE functions, mostly formatting dates -->

/**************************************************************************************
 BGN CONFIGURING AND QUERYING DATABASE + SETTING STRING VARS
	for each table we will query (each table put together in separate section)
		- prepare the string variables that will hold the row values
			DEPENDS ON INCLUSION OF "databaseVariables.php" where global db strings are initialized
		- build query syntax
		- make + run the queries
		- call fillStringVars() so field name variables get filled with live data
**************************************************************************/

	// Make + Run ISSUES TABLE query: build string holding SQL statement
$strSQL_selectIssues = "SELECT * FROM issuesBUFFER ";
	$strSQL_selectIssues .= " WHERE issuesBUFFER.issue_id = " . $issue_id . "";
	$strAppDebug .= "... strSQL_selectIssues = <strong>" . $strSQL_selectIssues . "</strong><br/>";
	//connect and query
$issuesResult = mysqli_query($mysqli, $strSQL_selectIssues) or die ('<h3 class="alert">Could not make + run ISSUES query: <br /><em>' . mysqli_error($mysqli) . "</em></h3>" );
if (! $issuesResult ) {
	echo '<h4 class="alert">ISSUES query problem in config</h4>';
} else {
	$row = mysqli_fetch_array($issuesResult, MYSQLI_ASSOC);
  $strAppDebug .= "issues query ok with " . $str_issue_id . ";";
  fillStringVars( "issues" );	//config.php
  //now ISSUES table data avail as strings for calling page
}//end if ISSUES ran ok

	// Make + Run PUBLICATIONS TABLE query: build string holding SQL statement
$strSQL_selectPublications = "SELECT * FROM publications ";
	$strSQL_selectPublications .= " WHERE publications.pub_id = '" . $str_pub_id . "'";
	$strAppDebug .= "strSQL_selectPublications = <strong>" . $strSQL_selectPublications . "</strong><br/>";
	//connect and query
$publicationsResult = mysqli_query($mysqli, $strSQL_selectPublications) or die ('<h3 class="alert">Could not make + run PUBLICATIONS query: <br /><em>' . mysqli_error($mysqli) . "</em></h3>" );
if (! $publicationsResult ) {
	echo '<h4 class="alert">PUBLICATIONS query problem in config</h4>';
} else {
	$row = mysqli_fetch_array($publicationsResult, MYSQLI_ASSOC);
  $strAppDebug .= "publications query ok with " . $str_pub_id . "; ";
  fillStringVars( "publications" );	//config.php
  //now PUBLICATIONS table data avail as strings for calling page
}//end if PUBLICATIONS ran ok

	//Make + Run FREQUENCIES TABLE query: see iconDateForm.php for actual query call
	//initialize fields from table: frequencies (using dummy values)
$str_MASTER_freq_id_code = "frequency table key";		$str_freq_word = "1-word freq description";
$str_freq_description = "full freq description";		$str_freq_note = "note me";
//end fields from table: frequencies, now build string holding SQL statement
$strSQL_selectFrequencies = "SELECT * FROM frequencies";

/* END CONFIGURING AND QUERYING DATABASE + SETTING STRING VARS
**************************************************************************************/




	//color vars can be alternated with setTRcolor(integer)
$strTRcolor 		= "#adbd90"; //darker
$strTRcolorAlt 	= "#e9e6d3";	//lighter


$strAppDebug .= "<p>end config.php</p>";

?>

<script language="javascript" type="text/javascript">
/******************************************************************************************
	Cookie functions after http://www.w3schools.com/js/js_cookies.asp
*/
function setCookie( cookieName, inValue, numDaysCookieLives ){
	//alert("setCookie( " + cookieName+ ", " +inValue+ ", " +numDaysCookieLives+ " )");
	var cookieExpirationDate = new Date();
	cookieExpirationDate.setDate( cookieExpirationDate.getDate() + numDaysCookieLives );
	var cookieValue=escape( inValue ) + ( (numDaysCookieLives==null) ? "" : "; expires=" + cookieExpirationDate.toUTCString() );
	document.cookie=cookieName + "=" + cookieValue;
}//end setCookie

function getCookie( cookieName ){
	var i, x, y, ARRcookies=document.cookie.split( ";" );
	for ( i=0; i < ARRcookies.length; i++ ){
		x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("=") );
		y = ARRcookies[i].substr( ARRcookies[i].indexOf("=")+1 );
		x = x.replace( /^\s+|\s+$/g, "" );
		if ( x==cookieName ) return unescape( y );
	}
}//end getCookie(cookieName)
/*	end cookie functions
*******************************************************************************************/

</script>



