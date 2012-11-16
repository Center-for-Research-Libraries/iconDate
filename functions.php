<?php

/**************************************************************************************/
/*	BEGIN FUNCTIONS		*/


/**************************************************************************************
// fillStringVars takes a string parameter which usually refers to a database table
		(but can be made to do other things).
		fillStringVars puts database or form data in string variables: because database data in PHP seems to disappear
			after being used once, this keeps the values available for the whole application.
*/
function fillStringVars($datasource){
	global $strAppDebug;
	global $row; //this record

		//fields in the issues record
	global $issue_id; //this one is extra
	global $str_issue_id,	$str_ISSUE_pub_id, $str_issue_date,	$str_rawIssueData, $str_repos_id,	$str_condition_id, $str_format;
	global $str_format_id, $str_archive_status_id, $str_provenance, $str_update_date, $str_issue_note;
	global $str_specificIssuesCreated, $str_specificIssuesCreatedDate;

		//fields in the publications record
	global $str_record_id, $str_issuesProcessed, $str_marc001, $str_pub_id, $str_marc001;
	global $str_pub_title, $str_pub_title_alt, $str_pub_city, $str_pub_state, $str_pub_country;
	global $str_pub_freq_id_code, $str_pub_bgnDate, $str_pub_endDate;
	global $str_date260C, $str_freq310, $str_fmrFreq321, $str_pub_freq_code321;
	global $str_date362, $str_numberingNote515, $str_summary520, $str_descriptionNote588;

		//fields in the frequencies record
	global $str_MASTER_freq_id_code, $str_freq_word, $str_freq_description, $str_freq_note;

	$strAppDebug .= "<hr/>fillStringVars(" . $datasource . "); ";
	if ($datasource == "issues") { //issuesBUFFER table
				$strAppDebug .= " entered the if 'issues';";
			$str_issue_id 		= trim($row["issue_id"]);
			$str_ISSUE_pub_id = trim($row["pub_id"]); //from issues record, separate copy for verification
			$str_pub_id 			= trim($row["pub_id"]);	//so fillStringVars works when called with 'publications' param
				$strAppDebug .= " str_pub_id = '<strong>" . $str_pub_id . "</strong>' set by fillStringVars('" . $datasource . "'); ";
			$str_issue_date 	= trim($row["issue_date"]);
			$str_rawIssueData = trim($row["rawIssueData"]);
			$str_repos_id			= trim($row["repos_id"]);
			$str_condition_id = trim($row["condition_id"]);
			$str_format				= trim($row["format"]);
			$str_format_id		= trim($row["format_id"]);
			$str_archive_status_id = trim($row["archive_status_id"]);
			$str_provenance		= trim($row["provenance"]);
			$str_update_date	= trim($row["update_date"]);
			$str_issue_note		= trim($row["issue_note"]);
			$str_specificIssuesCreated			= trim($row["specificIssuesCreated"]);
			$str_specificIssuesCreatedDate	= trim($row["specificIssuesCreatedDate"]);
			// end filling data for issues
	} elseif ($datasource == "publications"){
			$strAppDebug .= " entered the if 'publications';";
		$str_pub_id 						= trim($row["pub_id"]); //was set when we called fillStringVars('issues')
			$strAppDebug .= " str_pub_id = '<strong>" . $str_pub_id . "</strong>' set by fillStringVars('" . $datasource . "'); ";
		$str_marc001						= trim($row["marc001"]);
		$str_pub_title 					= trim($row["pub_title"]);
		$str_pub_city 					= trim($row["pub_city"]);

		$ctry 									= trim($row["country_id"]);
		$str_pub_state 					= strtoupper(substr($ctry, 0, 2));
		if (substr($ctry, 2, 1) == "u"){
			$str_pub_country 		= "United States";
		} elseif (substr($ctry, 2, 1) == "c"){
			$str_pub_country 		= "Canada";
		} else {
			$str_pub_country 		= $ctry;
		} //end str_pub_state + str_pub_country
			//pub_bgnDate and pub_bgnDate are sort of junk fields that did not necessarily come from the bib data
		$str_pub_bgnDate 				= trim($row["pub_bgnDate"]); 	$str_pub_endDate 				= trim($row["pub_endDate"]);
		$str_date260C 					= trim($row["date260C"]);
		$str_pub_freq_id_code 	= trim($row["freq_id_code"]);
		$str_freq310 			= trim($row["frequency310"]);
		$str_fmrFreq321 = trim($row["formerFrequency321"]);
		if ($str_fmrFreq321 != ""){
				//if we have a 321, determine a letter code: no code exists in the table
				//take up to first comma
			$fqWord = substr($str_fmrFreq321, 0, strpos($str_fmrFreq321, ","));
			$strAppDebug .= "<br/>fqWord=" . $fqWord;
			switch ($fqWord) {
			    case "Annual": 				$str_pub_freq_code321   = "a"; break;
			    case "Bimonthly": 		$str_pub_freq_code321   = "b"; break;
			    case "Semiweekly": 		$str_pub_freq_code321   = "c"; break;
			    case "Daily": 				$str_pub_freq_code321   = "d"; break;
			    case "Biweekly": 			$str_pub_freq_code321   = "e"; break;
			    case "Semiannual": 		$str_pub_freq_code321   = "f"; break;
			    case "Biennial": 			$str_pub_freq_code321   = "g"; break;
			    case "Triennial": 		$str_pub_freq_code321   = "h"; break;
			    case "Three times a week": $str_pub_freq_code321   = "i"; break;
			    case "Three times a month": $str_pub_freq_code321  = "j"; break;
			    case "Continuously updated": $str_pub_freq_code321 = "k"; break;
			    case "Monthly": 			$str_pub_freq_code321   = "m"; break;
			    case "Quarterly": 		$str_pub_freq_code321   = "q"; break;
			    case "Semimonthly": 	$str_pub_freq_code321   = "s"; break;
			    case "Weekly": 				$str_pub_freq_code321   = "w"; break;
			    case "Three times a year": $str_pub_freq_code321   = "t"; break;
			    default: 							$str_pub_freq_code321   = "";
			}//end switch
			$strAppDebug .= "<br/>str_pub_freq_code321=" . $str_pub_freq_code321;
		}//end figuring a code for 321
		$str_date362 						= trim($row["date362"]);
		$str_numberingNote515 	= trim($row["numberingNote515"]);
		$str_summary520 				= trim($row["summary520"]);
		$str_descriptionNote588 = trim($row["descriptionNote588"]);
		// end filling data for publications
	} elseif ($datasource == "frequencies") {
		$strAppDebug .= "<br/>entered the if 'frequencies';";
		$str_MASTER_freq_id_code 	= trim($row["freq_id_code"]);
		$str_freq_word					 	= trim($row["freq_word"]);
		$str_freq_description 		= trim($row["freq_description"]);
		$str_freq_note 					= trim($row["freq_note"]);
	} else { //for form data, or fileupload, or blank if form not submitted
		//see the original code in ./includes/applicationConfiguration.php
		$strAppDebug .= "<h4>fillStringVars hit the else...no output</h4>";
	}//end else not database
	$strAppDebug .= " end fillStringVars<hr/>";
}//end fillStringVars



/**************************************************************************************
	setSelectClass(integer): simple utility returns css class for styling <select><options>
		based on whether integer is odd or even
*/
function setSelectClass($thisInt){
	global $rtnClass;
	if (($thisInt % 2) != 0) {
		$rtnClass = "op1"; //darker
	} else {
		$rtnClass = "op0"; //lighter
	} //end if
	echo '<h4 class="alert">thisInt = ' . $thisInt . '; rtnClass = "' . $rtnClass . '"</h4>';
	return $rtnClass;
}//end setSelectClass



/**************************************************************************************
	setTRcolor(integer): simple utility returns color for styling, based on whether integer is odd or even
*/
function setTRcolor($str_record_id){
	global $strTRcolor, $strTRcolorAlt;
	if (($str_record_id % 2) != 0) {
		$strTRcolor 		= "#adbd90"; //darker
		$strTRcolorAlt 	= "#e9e6d3"; //lighter
	} else {
			$strTRcolor 		= "#e9e6d3";
			$strTRcolorAlt 	= "#adbd90";
	} //end if
	//echo "<h3>setTRcolor #" . $str_record_id . ": col=" . $strTRcolor . ", alt=" . $strTRcolorAlt . "</h3>";
	return $strTRcolor;
}//end setTRcolor




/*	END FUNCTIONS		*/
/**************************************************************************************/

?>