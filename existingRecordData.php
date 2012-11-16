
<?php
	/*
		- existingRecordData.php retrieves DB records to inform user about the details of
			- publication in publications table
			- issue in issuesBUFFER
		- VARIABLES ARE FILLED IN config.php, databaseVariables.php
			- this page is not meant to stand alone
	*/
?>

<div id="existingRecordData" class="boxData">
	<div id="publicationInfo" class="documentationReports">
		<span class="pubInfoHeader">Publication data:</span>
			id: <span class="important"><?php echo $str_pub_id; ?></span>
			<span class="pubData"><?php echo $str_pub_title; ?></span>
					<span class="important">(<?php echo $str_pub_city; ?>,
					 <?php echo $str_pub_state; ?>,
					 <?php echo $str_pub_country; ?>)
			</span>
		<br/>
		<span class="pubInfoHeader">Dates:</span>
			<?php
				if ($str_date260C != "") echo '260-C: <span class="pubData">' . $str_date260C . '</span>, ';
				else { echo '<span class="unimportant">no 260-C</span>; '; }
				if ($str_date362 != "") echo '362: <span class="pubData">' . $str_date362 . '</span>, ';
				else { echo '<span class="unimportant">no 362</span>; '; }
				if ($str_pub_bgnDate != "") echo 'other source: <span class="pubData">' . $str_pub_bgnDate . '</span>-';
				if ($str_pub_endDate != "") echo '-<span class="pubData">' . $str_pub_endDate . '</span>.';

				if ($str_date362 != "") echo "<br/>"; //unless there's a date362, no way do we need a line break

			?>

			<span class="pubInfoHeader">Frequencies:</span>

			<span class="pubInfoHeader">Most recent:</span>
				code: <span class="pubData" id="freq_id_code"><?php echo $str_pub_freq_id_code; ?></span>
				(<span class="pubData"><?php echo $str_freq310; ?></span>).&nbsp;

			<span class="unimportant">Former/earliest (ignore until further development):
				code: <?php echo $str_pub_freq_code321; ?>.&nbsp; statement: <?php echo $str_fmrFreq321; ?>
			</span>

		<br/>

			<?php
				if ($str_descriptionNote588 != ""){
					echo '<span class="pubInfoHeader">DBO/LIC:&nbsp;</span><span class="pubData">' . $str_descriptionNote588 . '</span>';
				}
				if (($str_descriptionNote588 != "") && ($str_summary520 != "")) { echo '<br/>'; }
				if ($str_summary520 != ""){
					echo '<span class="pubInfoHeader">Summary:</span><span class="pubData">' . $str_summary520 . '</span>,';
				}
			?>

			<!--issueInfo div inside publicationInfo div to reflect how it's a child of the pub-->
		<div id="issueInfo" class="documentationReports">
			table <span class="important"><?php echo $tableChoice; ?></span> has data:
				id:	<span class="highlightCode"><?php echo $str_issue_id; ?></span>
				pub: <?php echo $str_ISSUE_pub_id; ?>,
				held at <span class="important"><?php echo $str_repos_id; ?></span>,
				in format: <span class="important"><?php echo $str_format . " (" . $str_format_id . ")"; ?></span>,
				cond. <span class="important"><?php echo $str_condition_id; ?></span>,
				archive: <span class="important"><?php echo $str_archive_status_id; ?></span>.
				<?php
				if (($str_issue_date != "0000-00-00") && ($str_issue_date != "")){ //not default and not blank
					echo "specific date: " . $str_issue_date . "; ";
				}
				if ($str_issue_note != ""){
					echo "note: " . $str_issue_note . "; ";
				}
				?>
				<br/>original info updated: <span class="important"><?php echo $str_update_date; ?></span>
				<?php
				if (($str_specificIssuesCreated != 0) || ($str_specificIssuesCreatedDate != "0000-00-00")){
					echo "<br/>child issue records created: on " . $str_specificIssuesCreatedDate . "; ";
				}
				?>
			<br/>
			 dates held: <span class="rawIssueData"><?php echo $str_rawIssueData; ?></span>
		</div><!--end issueInfo-->
	</div><!--end publicationInfo-->
</div><!--end #existingRecordData-->
<!--end include existingRecordData.php-->
