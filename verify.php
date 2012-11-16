<?php
	/*
		- verify.php retrieves DB records to confirm to user that stored procedure has performed expected operation.
		- VARIABLES ARE FILLED IN config.php, databaseVariables.php
			- this page is not meant to stand alone: used in process.php
	*/
?>

<!--bgn include verify.php-->

<div id="verificationDiv" class="boxData">
			<!--issueInfo div inside publicationInf div to reflect how it's a child of the pub-->
			<?php
				// Build SQL to query: IN VERIFY, WE GET THE NEW ISSUES FOR THIS pub_id
			$thisTable = 'issuesNEW';
			$strSQL_issuesVERIFY = 'SELECT * FROM ' . $thisTable;
				$strSQL_issuesVERIFY .= ' WHERE ' . $thisTable . '.pub_id = "' . $str_pub_id . '"';
					/*
						use startDates + endDates supplied from user input + configured into array in databaseVariables.php
							- page that include this one has already verified that the 2 arrays are the same length, so 1 loop

					for ($i = 0; $i < count($startDates); $i++) {
						$strSQL_issuesVERIFY .= " AND (issue_date BETWEEN '" . $startDates[$i] . "' AND '" . $endDates[$i] . "')";
					}//end foreach startDate
					*/
					$strSQL_issuesVERIFY .= ' AND (issue_date >= "' . $startDates[0] . '") ';
					$strSQL_issuesVERIFY .= ' AND (issue_date <= "' . $endDates[count($endDates)-1] . '") ';
					$strSQL_issuesVERIFY .= ' AND issue_note LIKE "%' . $str_issue_id . '%"';


				$strAppDebug .= '<br/>... strSQL_issuesVERIFY = <strong>' . $strSQL_issuesVERIFY . '</strong><br/>';
				//echo "<br/>... strSQL_issuesVERIFY = <strong>" . $strSQL_issuesVERIFY . "</strong><br/>";

				//connect and query
				$rows = array();
				$verifyResult = $mysqli->query($strSQL_issuesVERIFY) or die ('<h3 class="alert">Could not make + run ISSUES query: <br /><em>' . mysqli_error($mysqli) . "</em></h3>" );
				if (! $verifyResult ) {
					echo '<h4 class="alert">issuesVERIFY query problem in config</h4>';
				} else {
			  	$strAppDebug .= 'issues query ok with ' . $str_issue_id . ';';
					while($row = $verifyResult->fetch_array()) {
 						$rows[] = $row;
 						$strAppDebug .= '<p class="msgVerification">' . count($rows) . ' rows) ' . $rows[count($rows)-1]['issue_date'] . '</p>';
 					}

 					$msgVerification = '<p class="msgVerification">';
 					$msgVerification .= '<span class="highlightCode"> ' . $str_pub_id . '</span>&nbsp;';
 					$msgVerification .= 'has <span class="highlightCode"> ' . count($rows) . '</span>&nbsp;';
 					$msgVerification .= 'added to ' . $thisTable . '&nbsp;';
 					$msgVerification .= 'between <span class="highlightCode">' . $startDates[0] . '</span>&nbsp;and <span class="highlightCode">' . $endDates[count($endDates)-1] . '</span>&nbsp;';
 					$msgVerification .= 'based on issues.<span class="highlightCode">' . $str_issue_id . '</span>.rawIssueData</p>';
 					echo $msgVerification;

					for($i = 0; $i < count($rows); $i++){
 					?>
 							<div id="issueInfo" class="documentationReports">
							<span class="important"><?php echo ($i + 1); ?></span>:
								id:	<span class="highlightCode"><?php echo $rows[$i]['issue_id']; ?></span>
								date: <span class="highlightCode"><?php echo $rows[$i]['issue_date']; ?></span>
								pub: <?php echo $rows[$i]['pub_id']; ?>,
								held at <span class="important"><?php echo $rows[$i]['repos_id']; ?></span>,
								in format: <span class="important"><?php echo $rows[$i]['format'] . " (" . $rows[$i]['format_id'] . ")"; ?></span>,
								condition <span class="important"><?php echo $rows[$i]['condition_id']; ?></span>,
								archive status: <span class="important"><?php echo $rows[$i]['archive_status_id']; ?></span>.
								<br/>note: <?php echo $rows[$i]['issue_note']; ?>;
								original info updated: <span class="important"><?php echo $rows[$i]['update_date']; ?>,</span>
								child issue records created? <?php echo $rows[$i]['specificIssuesCreated'] . ", " . $rows[$i]['specificIssuesCreatedDate']; ?>
						</div><!--end issueInfo-->
 					<?php
					}//end for each row

			  //now ISSUES table data avail as strings for calling page
			}//end if ISSUES ran ok
			?>
</div><!--end #verificationDiv-->

<!--end include verify.php-->

