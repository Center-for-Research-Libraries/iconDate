<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CRL Technical Services: ICON issue date calculator</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php

require_once('config.php');
echo $holdingsStylesLink; //in serverVariables.php: IP address always changing

?>

</head>

<!--setCookie/getCookie code is in config.php-->
<body onunload="setCookie('lastIssueProcessed', '<?php echo $str_issue_id; ?>', 15);">


<?php
include "header.php";
include "navigationHeader.php";
include "existingRecordData.php";

/**************************************************************/
//check the data before doing storedProcedureCall
	//array lengths: make sure they're equal before calling the procedure
$numStarts 	= count($startDates);		$numEnds 		= count($endDates);
	//$strAppDebug .= "<br/>numStarts = " . $numStarts . "; numEnds = " . $numEnds . "; ";

$badInput = false; //soooo optimistic
$errorMsg = "";
if ($numStarts != $numEnds) { // mismatched arrays
	$badInput = true;
	$errorMsg = "Number of start dates (<span class='highlightCode'>" . $numStarts . "</span>) is not the same as the number of end dates (<span class='highlightCode'>" . $numEnds . "</span>).&nbsp;&nbsp;";
}
if ($startDates[0] == ""){
	$badInput = true;		$errorMsg .= "Start date was blank.&nbsp;&nbsp;";
}
if ($endDates[0] == ""){
	$badInput = true;		$errorMsg .= "End date was blank.&nbsp;&nbsp;";
}
//end data checking
/**************************************************************/


if ($bShowDebug) echo $strAppDebug . "<hr/>";

if ( $badInput ){ //output error message, do no call to database
	echo "<h3 class='alert'>" . $errorMsg;
	echo "<br/>Please return to the input page using the 'back to previous issue' button at bottom left and fix the inputs.</h3>";
} else { //	good data, give it a try
	for($i=0; $i < count($startDates); $i++){
		/* after www.rvdavid.net/using-stored-procedures-mysqli-in-php-5/
			[from 2007] and http://us.php.net/manual/en/mysqli.quickstart.stored-procedures.php
			to resolve error:
				`Commands out of sync; You can't run this command now';
			- stored procedure gives 2 resultsets:
					One with actual resultset + 1 which sends status of stored procedure (OK/ERR)
			- need to handle 2nd resultset: soooo
			    A) buffer the resultsets				B) use or assign gathered data from 1st resultset
			    C) free 1st resultset						D) loop thru remaining resultset(s)			E) free them with each iteration.
		*/
		$storedProcedureCall = "CALL " . $storedProcedureName . "(" . $issue_id . ", ";
		$storedProcedureCall .= "'" . $startDates[$i] . "', '" . $endDates[$i] . "')";
		//echo "storedProcedureCall is " . $storedProcedureCall . "; now call it ...<br/>";

			//A) buffer the resultsets (databaseConnection is defined in databaseVariables.php)
		$issuesResult 			= $mysqli->query($storedProcedureCall) or die ('<h3 class="alert">Could not make + run ISSUES query: <br /><em>' . mysqli_error($databaseConnection) . '</em></h3>' );

		do { //mysqli.quickstart.stored-procedures.php - adding this solved 'strict' error that caused fail in 'if' after 'do'!
		    if ($spResult = $mysqli->store_result()) {
		        $spResult->free();
		    } else {
		        if ($mysqli->errno) {
		            echo "store_result failed: (" . $mysqli->errno . ") " . $mysqli->error;
		        }//end if
		    }//end else
		} while ($mysqli->more_results() && $mysqli->next_result());

		if ($issuesResult) { //check if the query was successful and inside the if: B) use or assign gathered data


			$data = $issuesResult->fetch_assoc(); //$issuesResult is of type mysqli_result: use data from resultset

			$display = $i+1 . ") entered issue(s) in range for <span class='first_date'>" . $startDates[$i] . "</span>-";
			$display .= "<span class='last_date'>" . $endDates[$i] . "</span>;<br/>";
			echo $display;

			$issuesResult->free(); //C) free the first resultset

			if ($mysqli->more_results()){ //C1)
					$c = 0;
				while ($mysqli->next_result()) { //D) loop thru remaining resultset(s) using next_result() method
					$issuesResult = $mysqli->use_result();
					if ($issuesResult instanceof mysqli_result) { // E) free remaining resultset(s) with each iteration
						$issuesResult->free();
					}//end if instanceof
				}//end while
			}//end if more_results()
		} else {
			//echo '<h3 class="alert">' . $i . ') issuesResult was not an instanceof mysqli_result.</h3>';
		}//end if $issuesResult
		//echo "<br/>end for: ";
	}//end for
}//end else (data passed validation)

include("verify.php");

?>



</body>
</html>

