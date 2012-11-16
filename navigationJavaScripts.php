<script language="JavaScript" type="text/javascript">
	/*
		- this script file is PHP to allow using database and server values
		- the JavaScript depends on inclusion of clientSide_issue_ids.js
		- get index of this issue id, plus prev and next ones, for linking between form pages
	*/

function gotoNewPage( thisHREF ){ //thisHREF is built below
	var thisURL = window.location.toString();
	if (thisURL.indexOf("process") != -1){ //never confirm departure on processing page
		location.href = thisHREF;
		return true;
	} else if (( $("#startDates").val() == "") && ( $("#endDates").val() == "")) {
			//when form data has not been changed, always allow departure
		location.href = thisHREF;
		return true;
	} else if (confirm("Skip this issue without saving?")) {
			//form data changed or not present so ask before leaving
		location.href = thisHREF;
	} //end if-else
}//end gotoNewPage




$(document).ready(function(){ //set default input values
	var prevIssueID = "";				//the actual IDs
	var thisIssueID = "<?php echo $str_issue_id; ?>";
	var nextIssueID = "";

	//alert( "in iconDateForm, thisIssueID=='" +thisIssueID+ "'\n clientSide_issue_ids.length = " + clientSide_issue_ids.length);

	for (var i=0; i < clientSide_issue_ids.length; i++){

		if (clientSide_issue_ids[i] == thisIssueID){
			prevIssueID = clientSide_issue_ids[i-1];
			nextIssueID = clientSide_issue_ids[i+1];
			//alert("at " +i+ ", prevIssueID=='" +prevIssueID+ "', nextIssueID=='" +nextIssueID+ "'");

			var thisURL = window.location.toString();
			var query_string = thisURL.split("?");
			/*
			//these work when we are going back to the same page,
				but when we want to move back from index.php to process.php,
				use values from serverVariables.php, incl. in config.php
			var prevURL = query_string[0] + "?" + "issue_id=" + prevIssueID;
			var nextURL = query_string[0] + "?" + "issue_id=" + nextIssueID; 			*/

			if (thisURL.indexOf("process") != -1) { //on processing page, prevURL goes back to last page
				var prevURL = thisURL.replace("process.php", "index.php")  + "?" + "issue_id=" + thisIssueID;
				var nextURL = thisURL.replace("process.php", "index.php")  + "?" + "issue_id=" + nextIssueID;
			} else {
				var prevURL = "<?php echo $thisUrl; ?>"  + "?" + "issue_id=" + prevIssueID;
				var nextURL = "<?php echo $thisUrl; ?>"  + "?" + "issue_id=" + nextIssueID;
			}

					//add event to button: for some reason we want to go back and look at the previous publication
			$("#prevIssueButton").click(function() { gotoNewPage( prevURL ); });
					//add event to button: this item is too complex, do the next without saving
			$("#nextIssueButton").click(function() { gotoNewPage( nextURL ); });
					//add event to span in dateInstruction.php: this item is too complex, do the next without saving
			$("#moveNextRecordSpan").click(function() { gotoNewPage( nextURL ); });

			break;
		}//end if
	}//end for
});//end doc.ready func
</script>
