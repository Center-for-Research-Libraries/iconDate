<!-- bgn include "dateInstructions.php"-->
<a name="dateInstructions"></a>
<div id="dateInstructionDiv" class="documentationProcessing">
	<h3 id="dateInstructionsHeader" class="pageHeader">About entering the data:</h3>
<ul>
	<li>Each user will have about 35000 issue records to process.<br/>
		Bookmark the page with the input form at the end of the day so you easily can come back to where you started the next time you come to work. <br/>
		This will save you from having to hit 'forward to next issue' thousands of times.
	</li>
	<li>In the <span class="first_date">first</span> and <span class="last_date">last</span> issue date fields, use <span class="important">YYYY-MM-DD</span> format</li>
	<ul>
		<li>4 digit <span class="important">Y</span>ear<span class="important">-</span>2 digit <span class="important">M</span>onth<span class="important">-</span>2 digit <span class="important">D</span>ay</li>
		<li>Use leading zeroes: 1899-<span class="important">0</span>1-<span class="important">0</span>9 means January 9, 1899 <span class="unimportant">this should happen automatically (enter single-digit months/days and leading zeroes are added)</span></li>
		<li>Replace colon ('<span class="littleAlert">:</span>') with dash ('<span class="littleAlert">-</span>') <span class="unimportant">this should happen automatically.</span></li>
	</ul>
	<li>in <span class="rawIssueData">raw issue data</span>, where you get the dates for the <span class="first_date">first</span> and <span class="last_date">last</span> issue date fields:
		<ul>
			<li>angle brackets (<span class="littleAlert">&lt;</span> &nbsp; <span class="littleAlert">&gt;</span>) enclose sets of dates; do not enter them in the date fields <span class="unimportant">(but they should get stripped automatically)</span></li>
			<li>dash ('<span class="littleAlert">-</span>') represents a range
					<ul>
					<li>
						<span class="important">&lt;1899:07:22<span class="littleAlert">-</span>1900:12:15&gt;</span>
						means from July 22, 1899 to December 15, 1900;<br/>
						enter <span class="first_date">1899-07-22</span>-<span class="last_date">1900-12-15</span>
						and set these dates <span class="unimportant">i.e., click that button</span>
					</li>
					<li>
						<span class="important">&lt;1899:07:22<span class="littleAlert">-</span>12:15&gt;</span>
						means from July 22 to December 15, 1899;<br/>
						enter <span class="first_date">1899-07-22</span>-<span class="last_date">1899-12-15</span>
						and set these dates <span class="unimportant">i.e., click that button</span>
						<br/>(year may be repeated when it's the same, or not);
					</li>
					</ul>
			</li>
			<li>comma ('<span class="littleAlert">,</span>') represents a gap: nothing between the dates given
					<ul>
					<li>
						<span class="important">&lt;1899:7:22<span class="littleAlert">,</span> 12:15&gt;</span> means just July 22, 1899 and December 15, 1899 (2 dates same year)
						<br/>enter <span class="first_date">1899-07-22</span>-<span class="last_date">1899-07-22</span> <span class="unimportant">and set these dates</span>
						<br/>then &nbsp;<span class="first_date">1899-12-15</span>-<span class="last_date">1899-12-15</span> <span class="unimportant">and set these dates</span>
					</li>
					<li>
						<span class="important">&lt;1899:7:22<span class="littleAlert">,</span>30&gt;</span> means just July 22, 1899 and July 30, 1899 (2 dates same year, same month)
						<br/>enter <span class="first_date">1899-07-22</span>-<span class="last_date">1899-07-22</span> <span class="unimportant">and set these dates</span>
						<br/>then &nbsp;<span class="first_date">1899-07-30</span>-<span class="last_date">1899-07-30</span> <span class="unimportant">and set these dates</span>
					</li>
					<li>
						<span class="important">&lt;1835:1:8<span class="littleAlert">,</span>15<span class="littleAlert">,</span>2:12<span class="littleAlert">,</span>19<span class="littleAlert">,</span>3:12<span class="littleAlert">,</span>10:8<span class="littleAlert">,</span>12:17&gt;</span>
						means just Jan. 8 and 15, Feb. 12 and 19, March 12, Oct. 8, Dec. 17, all in 1835.
						<br/>That's 7 date ranges in 1 set of brackets, enter each as single dates (see below).
					</li>
					</ul>
			</li>
			<li>for single date, use the same date for first and last</li>
			<ul>
				<li><span class="rawIssueData">&lt;1900:6:23&gt; &lt;1912:7:4&gt;</span> needs 2 separate sets of dates:
					<ul>
						<li>enter
								<span class="first_date">1900-06-23</span>-<span class="last_date">1900-06-23</span>
								as first + last date of 1st range
						</li>
						<li>enter
								<span class="first_date">1912-07-04</span>-<span class="last_date">1912-07-04</span>
								as first + last date of 2nd range
						</li>
<!--li>add the fields for the 2nd (and beyond) date ranges with 'add another set of dates' button next to the 1st set</li-->
					</ul>
			</ul>
			<li>ignore years before angle brackets: raw issue data for the above example was '<span style="border:#e97100 thin solid; padding:0px 2px 0px 2px;">1900,1912 &lt;1900:6:23&gt; &lt;1912:7:4&gt;</span>'
			<li>ignore whole dates before angle brackets: you may see data like '<span style="border:#e97100 thin solid; padding:0px 2px 0px 2px;">June 23, 1900-July 4, 1912 &lt;1900:6:23-1912:7:4&gt;</span>'
			<li>if there are no angle brackets:
				<ul>
					<li>you may still be able to figure out the date or date ranges:</li>
					<li>for example, <span class="rawIssueData">1900/Nov 15</span> is a single date to enter as:
						<span class="first_date">1900-11-15</span>-<span class="last_date">1900-11-15</span>
					</li>
				<li>if it's just some years, or there is incomplete data for some ranges, or you can't figure it out, <br/>
					you can <span class="important">bail out</span>:
					<!--moveNextRecordSpan click function in navigationjavaScripts.js-->
					<span id="moveNextRecordSpan" class="actionButton"> move to next issue </span>.</li>

				</ul>
			</li>
			<li>for monthly publications, raw issue data may have no day: use the first of the month</li>
			<ul>
				<li>So for a date like <span class="rawIssueData">&lt;1931:5&gt;</span>
					<li>See the publication info at the top of the page</li>
					<li>Verify the frequency is a monthly</li>
					<li>
						first + last date of this range will be
						<span class="first_date">1931-05-01</span>-<span class="last_date">1935-05-01</span>
						<span class="unimportant">sample is from <a href="http://192.168.1.195/IMLS/iconDate/index.php?issue_id=36590019" target="_blank">issue id 36590019</a></span>
					</li>
			</ul>
			</li>

		</ul>
	</li>
</ul>

<a href="#createNewIssueForm" id="rtnToFormButton" class="actionButton" style="text-decoration:none;">Back to the date form</a>

</div><!-- end #dateInstructionDiv-->