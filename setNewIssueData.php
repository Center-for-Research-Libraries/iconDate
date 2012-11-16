<a name="createNewIssueForm"></a>
<div id="createNewIssueDiv" class="boxData">
<form action="process.php" method="post" id="createNewIssueForm" name="createNewIssueForm"
	onsubmit="if(! confirm('Submit these dates?')) return false;">

		<!-- startDates and endDates get multiple sets of dates and are read into arrays in process.php-->
	<input id="startDates" name="startDates" class="first_date" type="hidden" value="" />
	<input id="endDates" name="endDates" class="last_date" type="hidden" value="" />
	<input id="storedProcedureName" name="storedProcedureName" type="hidden" value="createNewIssue" />
	<input id="issue_id" name="issue_id" type="hidden" value="<?php echo $str_issue_id; ?>"/>
	<input id="pub_id" name="pub_id" type="hidden" value="<?php echo $str_pub_id; ?>"/>
	<input id="rawIssueData" name="rawIssueData" type="hidden" value="<?php echo $str_rawIssueData; ?>"/>

<div id="dateFields" class="documentationProcessing">
		<h3 id="dateInstructionsHeader" class="pageHeader">Enter and submit the data:</h3>

<?php
	include("warningAlreadyProcessed.php");
?>

		<div id="datesEnteredDisplay" style="height:100%">
			<span class="smallPageHeader">Dates entered so far:</span><br/>
		<!--append values of previous sets of date fields here, just for display:
				real data goes in startDates, endDates fields-->
		</div><!--end #datesEnteredDisplay-->
		<ol id="dataPreparationSteps">

			<li>
				Read the <span class="rawIssueData">raw issue data</span> (date format instructions <a href="#dateInstructions">below</a>)
				<br/>
					<!--rawForUser is reformatted in the document.ready function and dateScripts.js-->
				<span id="rawForUser" class="rawIssueData"><?php echo $str_rawIssueData; ?></span>

			</li>

			<li>
				<!--scripting for date fields and buttons is at page bottom-->
				For each date range there, insert dates of
				<br/>
				<span class="first_date">
					first issue
					<input id="first_date" name="first_date" type="text" class="first_date" value="<?php echo $str_date260C; ?>" size="20" onfocus="this.select();" />
				</span>
				<br/>
				<input id="copyDateButton" name="copyDateButton" type="button" class="actionButton" value="copy date--&gt;" />
				<br/>
				<span class="last_date">
					last issue
					<input id="last_date" name="last_date" type="text" class="last_date" value="<?php echo $str_date260C; ?>" size="20" onfocus="this.select();" />
				</span>&nbsp;(use 'copy date' or just type the real final date in the range).
			</li>
			<li>
				When you are sure you have both dates correct:
				<input id="setDatesButton" name="setDatesButton" type="button" class="actionButton" value="set these dates" />
				and "Dates entered..." box will update.
				<br/>If you make a mistake in the dates, you'll have to reload the page and start over.
			</li>
			<li>
				Repeat the steps up to here as needed (there may be many date ranges).
			</li>
			<li>
					When all date ranges have been entered: <input id="createNewIssueButton" name="createNewIssueButton" type="submit" class="actionButton" value="create the new issues for this list of dates" />
					<br/>Be patient: long date ranges may take time.
			</li>
		</ol>
</div><!--end #dateFields-->
</form><!--end #createNewIssueForm -->
</div> <!--end #createNewIssueDiv-->