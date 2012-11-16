<script language="javascript" type="text/javascript">

// AJE functions, mostly formatting dates

/*****************************************************************
	renderISODate(thisDate)
		- reformat date to the kind of string we want
*/
function renderISODate(thisDate){ //
	var isoDate = thisDate.getFullYear() +"-";

	if ( (thisDate.getMonth()+1) < 10){ isoDate += "0"; }//add leading 0 to month number

	isoDate += (thisDate.getMonth()+1) +"-"; //months are array indices, 0-11

	if ( (thisDate.getDate()+1) <= 10){ isoDate += "0"; } //add leading 0 to day number

	isoDate += thisDate.getDate(); //dates are normal numbers

	return isoDate;
}//end renderISODate




/*****************************************************************
	calculatePublishedDates( d1, d2, frequency )
		- gets 2 text fields with date strings, and
		- frequency of publication (from DB or SELECT object or SELECT object value filled in from a DB
		- based on d1 (first date value), and frequency, calculates dates that should have been published until end date (d2)
		- fills in a textarea with this info
		- this function is not used in iconDate as of 2012-June-07
			- replaced by stored procedure createNewIssue in mySQL, so PHP submits the 2 dates and mySQL calculates
*/
function calculatePublishedDates( d1, d2, frequency ){
	//var selFreq = $("select#frequency");//publication frequency from form select

	if(frequency == "dummyFrequency"){ //invalid frequency, notify and bail out
		$("select#frequency"  ).removeClass("highlightCode");
		$("select#frequency"  ).addClass("alert");
		alert("Please choose a valid frequency from the list");
		$("select#frequency"  ).focus();
		return;
	}//end if

	var strDebug = "calculatePublishedDates( d1: "+d1+", d2: "+d2+", fq: "+frequency+")\n";
		// input dates come in as STRINGs; next, make Date objects to manipulate with date.js methods
		//Date.clone() --> http://code.google.com/p/datejs/wiki/APIDocumentation
	var first_date 	= Date.parse(d1);
	var prevDate 		= first_date.clone();  // gets incremented + saved; is basis for currentDate (below)
	var currentDate = prevDate.clone(); 	// holds issue date
	var last_date  	= Date.parse(d2);
		strDebug += "currentDate = '"+currentDate+"'; val type " +typeof(currentDate)+";\n";
		$("textarea#dateCalcResult").val($("textarea#dateCalcResult").val() + "\n" + strDebug);

	var pub_id 			= $("input#pub_id").val();
	var returnData = "\n" + pub_id + "|"; //what gets saved
	var strDate = renderISODate(first_date);
	returnData += strDate;

	var bContinue = -2; //LOOP CONTROLLER: see end of while loop
		bContinue = currentDate.compareTo( last_date );
	var i = 1;					//loop counter
	while (bContinue < 0){

		strDebug += "while "+i+")\n\tcurrentDate bgn = '" +currentDate.toString()+ "';\n";

		switch(frequency){
			case "d": { //d Daily. issued/updated once a day. May include Sat. + Sun.
				currentDate = prevDate.add(1).days();
				strDebug += "\tfreq-D, now "+currentDate.toString()+"\n";
				break;
			}
			case "i": { //i Three times a week
				strDebug += "\tfreq-I, now "+currentDate.toString()+"\n";
strDebug += "\tfreq-I NOT READY TO USE\n";
currentDate = last_date.add(1).days();
				break;
			}
			case "c": { //c Semiweekly. issued/updated twice a week.
				strDebug += "\tfreq-C, now "+currentDate.toString()+"\n";
strDebug += "\tfreq-C NOT READY TO USE\n";
currentDate = last_date.add(1).days();
				break;
			}
			case "w": { //w Weekly. Issued once a week.
				currentDate = prevDate.add(1).weeks();
				strDebug += "\tfreq-W, now "+currentDate.toString()+"\n";
				break;
			}
			case "j": { //j Three times a month
				strDebug += "\tfreq-J, now "+currentDate.toString()+"\n";
strDebug += "\tfreq-j NOT READY TO USE\n";
currentDate = last_date.add(1).days();
				break;
			}
			case "e": { //e Biweekly. issued/updated every two weeks.
				currentDate = prevDate.add(2).weeks();
				strDebug += "\tfreq-E, now "+currentDate.toString()+"\n";
				break;
			}
			case "s": { //s Semimonthly. issued/updated twice a month.
				currentDate = prevDate.add(2).weeks();
				strDebug += "\tfreq-S, now "+currentDate.toString()+"\n";
				break;
			}
			case "m": { //m Monthly. issued/updated every month.
				//Includes frequencies of 9, 10, 11 or 12 nos. a year.
				currentDate = prevDate.add(1).months();
				strDebug += "\tfreq-M, now "+currentDate.toString()+"\n";
				break;
			}
			case "b": { //b Bimonthly. issued/updated every 2 months, or 6, 7 or 8 nos. a year.
				currentDate = prevDate.add(2).months();
				strDebug += "\tfreq-B, now "+currentDate.toString()+"\n";
				break;
			}
			case "q": { //q Quarterly. issued/updated every 3 months, or 4 nos. a year.
				currentDate = prevDate.add(3).months();
				strDebug += "\tfreq-Q, now "+currentDate.toString()+"\n";
				break;
			}
			case "t": { //t Three times a year
				currentDate = prevDate.add(4).months();
				strDebug += "\tfreq-T, now "+currentDate.toString()+"\n";
				break;
			}
			case "f": { //f Semiannual. issued/updated twice a year, or two nos. a year.
				strDebug += "\tfreq-F, now "+currentDate.toString()+"\n";
strDebug += "\tfreq-F NOT READY TO USE\n";
currentDate = last_date.add(1).days();
				break;
			}
			case "a": { //a Annual. issued/updated once a year.
				currentDate = prevDate.add(1).years();//
				strDebug += "\tfreq-A, now "+currentDate.toString()+"\n";
				break;
			}
			case "g": { //g Biennial. issued/updated every 2 years.
				currentDate = prevDate.add(2).years();
				strDebug += "\tfreq-G, now "+currentDate.toString()+"\n";
				break;
			}
			case "h": { //h Triennial. issued/updated every 3 years.
				currentDate = prevDate.add(3).years();
				strDebug += "\tfreq-H, now "+currentDate.toString()+"\n";
				break;
			}
			case "u": { //u Unknown. The current frequency is not known.
				strDebug += "\tfreq-U, now "+currentDate.toString()+"\n";
strDebug += "\tfreq-U DOESN'T KNOW WHAT TO DO\n";
currentDate = last_date.add(1).days();
				break;
			}
			case "z": { //z Other. The frequency of the item cannot be defined by any of the other codes.
				strDebug += "\tfreq-Z, now "+currentDate.toString()+"\n";
strDebug += "\tfreq-Z DOESN'T KNOW WHAT TO DO\n";
currentDate = last_date.add(1).days();
				break;
			}
		}//end switch

			//LOOP CONDITIONS
			/*     //http://code.google.com/p/datejs/wiki/APIDocumentation
				.compareTo ( Date date ) : Number
					Compares this instance to a Date object, returns numeric indication of their relative values.
					-1 = this is lessthan date. //  0 = values are equal // 1 = this is greaterthan date. */
		strDebug += "bContinue will compare current:"+renderISODate(currentDate)+" to last: " +renderISODate(last_date)+"\n";
		bContinue = currentDate.compareTo( last_date ); strDebug += "\tbContinue == "+bContinue+"\n";
		i++;
		prevDate = currentDate.clone(); //hold this date for next pass thru loop

		if (bContinue < 0){ //without this check, we always go one iteration too far
			strDate = renderISODate(currentDate);
			strDebug += "\tcurrentDate end = '" +currentDate+ "\n";
			returnData += "\n" +pub_id+ "|" +strDate; //append ouput
		}




		$("textarea#dateCalcResult").val($("textarea#dateCalcResult").val() + "\n" + strDebug);

	}//end while

		//don't forget add last_date to output
	returnData += "\n" + pub_id +"|"+ renderISODate(last_date);

		//save output to form field
	$("textarea#dateCalcResult").val($("textarea#dateCalcResult").val() + "\n" + returnData);

		//remind about formerFrequency321 if it exists
	if( $("#formerFrequency321").html() != "" ){ //then the formerFrequency321 span is not empty, though hidden
		$("#container321").show();
	}//end if

}//end calculatePublishedDates


/*****************************************************************
	setFirstAndLastDates()
		- gets date fields from publications table via PHP
		- tries to fill in date fields with good default data (best guess as to what the bgn/end dates are)
		- not called or working in new version of iconDate setup: needs to be in a PHP file to get date field data
*/
function setFirstAndLastDates(){
	//var d1 	= Date.today().toISOString();//WORKS
	//var d1 	= Date.today().getYear() +"-"+ Date.today().getMonth +"-"+ Date.today.getDate();
	var d1 = "date1";
	var d2 = "date2";

		//362 is db field with most complete info, if present, example:
		//Vol. 122, no. 47 (Nov. 21, 1995)-v. 126 no. 101 (Dec. 29, 1999)
		//1st year, no. 1 (Feb. 2, 1920)-3rd year, no. 274 (Dec. 22, 1922)
	var d362 = new String("<?php echo $str_date362; ?>");
	var d260C = new String("<?php echo $str_date260C; ?>"); //alternates
	if (d362 != ""){
		var bgn = d362.indexOf("(")+1;
		var end = d362.indexOf(")");
		d1 = d362.substring(bgn, end);
		bgn = d362.lastIndexOf("(")+1;
		end = d362.lastIndexOf(")");
		d2 = d362.substring(bgn, end);
	} else if (d260C != "") {}
	else { //no good MARC-derived date fields present
		d1 =  new String("<?php echo $str_pub_bgnDate; ?>");;
		d2 =  new String("<?php echo $str_pub_endDate; ?>");;
	}//end else

	//alert(d1 + "\n" +d2)

		//put the date vars in the text fields
	$("input#first_date").val(d1);
	$("input#last_date").val(d2);

}//end setFirstAndLastDates



/*****************************************************************
	formatVerifyDates( dateFieldObject )
		- dateFieldObject is either first_date or last_date
		- fix up the dateFieldObject.value and warn about invalid data
*/
function formatVerifyDates( dateFieldObject ){
	var strDebug = "formatVerifyDates\n";

	var myDateValue = dateFieldObject.val();
	if (myDateValue == "") return false;

	//now replace all unwanted chars
		strDebug += "myDateValue before ':' replace: " +myDateValue+ "\n";
	myDateValue = myDateValue.replace(/:/g, "-");
		strDebug += "myDateValue after ':'  replace: " +myDateValue+ "\n";

		// String.replace takes a regex, so build one
	var regexRemove = /[\u003C\u003E\s]/g; //angle brackets, whatever is junk can go here
		/* https://developer.mozilla.org/en/Core_JavaScript_1.5_Guide/Regular_Expressions
				\xhh 	Matches the character with the code hh (two hexadecimal digits)
				\uhhhh 	Matches the character with the code hhhh (four hexadecimal digits).
			003C = <	;	003E = > ;			*/
		strDebug +=  "testing regex returns " + regexRemove.test(myDateValue)+ "\n";
	myDateValue = myDateValue.replace(regexRemove, "");
		strDebug += "myDateValue after 'regexRemove'  replace: " +myDateValue+ "\n";
	//now replacements are done

	//begin proper date formatting
	var bParse = Date.parse(myDateValue); //returns null if a bad value
	if (bParse) { // Date was ok
			strDebug += "GOOD bParse=" +bParse+ "\nDate.parse by itself returns = \n" +Date.parse(myDateValue);
		//myDateValue = Date.parse(myDateValue).toISOString();//THIS WORKS but chaining can fail
		myDateValue = Date.parse(myDateValue); //now parse for real
		myDateValue = myDateValue.toISOString(); // put it in YYYY-MM-DD format
			strDebug += "\nmyDateValue after toISOString\n" + myDateValue;

			//now replacements are done, add leading zeroes etc
		myDateValue = myDateValue.substr(0, 10); 						//so just take 1st 10 chars of the ISOString
		dateFieldObject.val(myDateValue);
		//alert(strDebug);
		dateFieldObject.removeClass("alert");
		return true;
	} else { //unparseable Date
		strDebug += "BAD Date.parse=" +bParse;
		dateFieldObject.addClass("alert");
		dateFieldObject.val("date is invalid");
		//alert(strDebug);
		dateFieldObject.focus();
		return false;
	}//end if/else of Date.parse
}//end formatVerifyDates



/*****************************************************************
		appendDateInput(dateArrayField, dateSimpleField)
			- dateArrayField is form field holdings all the dates: these will be used by php as an array of dates
			- dateSimpleField is where user enters sets of dates
			- appendDateInput puts the new entered date in the real field
			- and erases dateSimpleField data

*/
function appendDateInput(dateArrayField, dateSimpleField){
		//first set the form data
	var appendVal = dateArrayField.val();
	if (appendVal != ""){ //form field aready has data, add separator
		appendVal += "|";
	}
	appendVal += dateSimpleField.val();
	dateArrayField.val(appendVal);

		//update the user's display in div datesEnteredDisplay
		//capture the hidden fields, put into arrays
	var dateInfo = $("#startDates").val();
	var d1Array = dateInfo.split("|");
			dateInfo = $("#endDates").val();
	var d2Array = dateInfo.split("|");

	$("#datesEnteredDisplay").html(""); //clear the display area

	$("#datesEnteredDisplay").append('<span class="smallPageHeader">Dates entered so far:</span><br/>');
	for(var i = 0; i < d1Array.length; i++){ //fill the display area with the date values, formatted
		var d1 = String.concat('<span class="first_date">', d1Array[i], '</span>');
		var d2 = String.concat('-<span class="last_date">', d2Array[i], '</span><br/>');
		$("#datesEnteredDisplay").append(d1);
		$("#datesEnteredDisplay").append(d2);
	}//end for


	dateSimpleField.val(""); //wipe out first_date, last_date

} //end appendDateInput



/*****************************************************************
	function formatRawForUser highlights the commas and dashes
		in the rawIssueData, after the first angle bracket
*/
function formatRawForUser(){
		// highlight the commas and dashes in the rawIssueData
		var newRaw = $("#rawForUser").text();
		var startPos = newRaw.indexOf("<");
		var strDebug = "startPos=" + startPos;
		var sp1 = '<span class="littleAlert">';
		var sp2 = '</span>';
		var newComma = sp1 + "," + sp2;
		var newDash  = sp1 + "-" + sp2;

		var reComma = /,/g; //regular expressions
		var reDash  = /\-/g;

		strDebug += "\nBEFORE) newRaw.indexOf(',', startPos) = " +newRaw.indexOf(",", startPos);
		strDebug += "\nnewRaw.indexOf('-', startPos) = " +newRaw.indexOf("-", startPos);
		if (newRaw.indexOf(",", startPos) != -1){
			var nr1 = newRaw.substring(0, startPos); //capture the beginning
			var nr2 = newRaw.substring(startPos); //capture the end
			nr2 		= nr2.replace(reComma, newComma);
			strDebug += "\ncommma nr1 = " +nr1+ " and nr2 = " +nr2;
			newRaw = nr1 + nr2;
		}
		if (newRaw.indexOf("-", startPos) != -1){
			var nr1 = newRaw.substring(0, startPos); //capture the beginning
			var nr2 = newRaw.replace(reDash, newDash);
			strDebug += "\ndash nr1 = " +nr1+ " and nr2 = " +nr2;
			newRaw = nr1 + nr2;
		}
		strDebug += "\nAFTER) newRaw.indexOf(',', startPos) = " +newRaw.indexOf(",", startPos);
		strDebug += "\nnewRaw.indexOf('-', startPos) = " +newRaw.indexOf("-", startPos);
		strDebug += "\nnewRaw=" +newRaw;
		//alert(strDebug);

		$("#rawForUser").html(newRaw); //the money line
}//end formatRawForUser


	$(document).ready(function(){
			//wipe out any old date values retained so that reload starts clean
		$("#startDates").val("");
		$("#first_date").val("");
		$( "#endDates" ).val("");
		$( "#last_date").val("");

		formatRawForUser(); //in dateScripts.js

			//add events to date fields and buttons: functions called are in dateScripts.js
		$("#copyDateButton").click(function() {
			var success = formatVerifyDates($("#first_date")); // validate first_date
			if (success){
				var d1 = $("#first_date").val(); 	// get first_date value
				$("#last_date").val(d1);					// put it in last_date field
			}//end if
		});

			//change functions just format and validate: see setDatesButton for what updates real form data
		$("#first_date").change(function() {
			formatVerifyDates($("#first_date"));
		});
		$("#last_date").change(function() {
			formatVerifyDates($("#last_date"));
		});

		$("#setDatesButton").click(function() {
			if (	(formatVerifyDates($("#first_date"))) && (formatVerifyDates($("#last_date")))	) {
				// if both form fields are good
				appendDateInput($("#startDates"), $("#first_date"));
				appendDateInput($("#endDates"), $("#last_date"));
			}
		});

	});//end doc.ready func

</script>