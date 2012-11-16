<?php
	/*
		- warningAlreadyProcessed.php retrieves DB records to check whether the issue_id (from issuesBUFFER)
		is already found in issue_note fields in issuesNEW:
		if true, this means that the issue from issuesBUFFER has already had individual issues created in issuesNEW
		- VARIABLES ARE FILLED IN config.php, databaseVariables.php
			- this page is not meant to stand alone: used on index.php (included in setNewIssueData.php)
	*/
?>

<!--bgn include warningAlreadyProcessed.php-->

<?php
	// Build SQL query: IN WARNING, WE CHECK FOR issuesBUFFER.issue_id (submitted in form data/query string)
	// 	IN issuesNEW.issue_note
$thisTable = "issuesNEW";
$strSQL_checkAlreadyProcessed = "SELECT * FROM " . $thisTable;
	$strSQL_checkAlreadyProcessed .= " WHERE issuesNEW.issue_note LIKE '%" . $str_issue_id . "%'";

	$strAppDebug .= "<br/>... strSQL_checkAlreadyProcessed = <strong>" . $strSQL_checkAlreadyProcessed . "</strong><br/>";
	//echo "<br/>... strSQL_checkAlreadyProcessed = <strong>" . $strSQL_checkAlreadyProcessed . "</strong><br/>";

	//connect and query
	$warningResult = $mysqli->query($strSQL_checkAlreadyProcessed) or die ('<h3 class="alert">Could not make + run ISSUES query: <br /><em>' . mysqli_error($mysqli) . "</em></h3>" );
	if (! $warningResult ) {
		echo '<h4 class="alert">issuesWARNING query problem in config</h4>';
	} else {
  	$strAppDebug .= "issues query ok with " . $str_issue_id . ";";
  	$processedRecords = 0;
		while($row = $warningResult->fetch_array()) {
			$rows[] = $row;
			$processedRecords = count($rows);
		}
		if ($processedRecords != 0){ //previously added issues: bail out
			$bailoutWarning =  '<span class="alert" style="text-align:center;">' . $thisTable . ' already has <span class="highlightCode">' . $processedRecords . '</span> records ';
			$bailoutWarning .=  'for <span class="highlightCode">' . $str_pub_id . '</span> ';
			$bailoutWarning .=  'from issue <span class="highlightCode">' . $str_issue_id . '</span>. ';
			$bailoutWarning .=  'DO NOT PROCESS any more: go to next issue.</span>';
		} else { //no previously processed records
			$bailoutWarning =  '<span class="first_date" style="text-align:center;">';
			$bailoutWarning .= 'No records in ' . $thisTable . ' for publication ' . $str_pub_id . ' ';
			$bailoutWarning .=  'based on issue id ' . $str_issue_id . ': it is safe to process.</span>';
		}
	}//end else query executed ok
	echo $bailoutWarning;
?>
<!--end include warningAlreadyProcessed.php-->

