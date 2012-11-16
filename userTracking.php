<!--bgn include userTracking.php
		- 2012-June-12; 2012-Nov-1
		- sets cookies set in JavaScript; setCookie() / getCookie() functions in config.php
		- depends on presence of clientSide_issue_ids.js, previously included unless AJE screwed up
-->

<script language="JavaScript" type="text/javascript">

	$(document).ready(function(){

	function isValidUser( strUser, userLength ){
		var bValidUser = false;
		for(var i=0; i < userLength; i++) {
			if (strUser == userList[i]){
				bValidUser 		= true;
				setCookie('userName', strUser, 15);
				thisUserStart = userStartingPoints[i]; //for first time
				alert("userStartingPoints["+i+"]=='" +userStartingPoints[i]+"'");
				setCookie("userStart", thisUserStart, 15); //so avail on subsequent loads
				break;
			}//end if: submitted user name was found in array
		}//end for
		return bValidUser;
	}//end isValidUser

			//related to userName
		var userList 		= new Array( "user0", "user1", "user2" );
		var userLength 	= userList.length;
		var uPrompt = "Enter a user name: allowable values are: ";
		for(var i=0; i < userLength; i++) uPrompt += "\n\t" +userList[i];
		uPrompt += "\n";
			// determine starting position for each user, value in clientSide_issue_ids where user starts work 1st time
		var clientLength = clientSide_issue_ids.length; 			// 119641 issue_ids
		var clientDivIndex = Math.round(clientLength/userLength);

		var strDebug = "there are clientLength=" +clientLength+ " issue_ids and userLength=" +userLength+ " users";
		strDebug += "(clientLength/userLength) = (" +clientLength+ "/" +userLength+ "); and clientDivIndex = " +clientDivIndex+ "\n";

		var user0starts  = clientSide_issue_ids[0];										// '36589866'
		var user1starts  = clientSide_issue_ids[clientDivIndex]; 			// '36757018'
		var user2starts  = clientSide_issue_ids[clientDivIndex * 2]; 	// '36892541'

		var userStartingPoints = new Array(user0starts, user1starts, user2starts); // userStartingPoints matches userList
		var thisUserStart 		= getCookie("userStart"); // originally set in isValidUser( strUser )
		var strFormPageLink		= '<a href="http://<?php echo $thisServerIP . $thisUrl;?>?issue_id=';
		var linkStyle 				= '" style="color:#000000">';
			//end userList and userStartingPoints setup
		strDebug += "\n\nok ... userStartingPoints[0]=='" +userStartingPoints[0]+"'\nuserStartingPoints[1]=='" +userStartingPoints[1]+ "\nuserStartingPoints[2]=='" +userStartingPoints[2]+ "\nuser0starts=" +user0starts+ "\nuser1starts=" +user1starts+ "\nuser2starts=" +user2starts
		//alert(strDebug);

		var strUserCookie 	= getCookie('userName');
		var numUser = -1;
		if (!strUserCookie) {
			var userName = new String(prompt(uPrompt, ""));
			while (! isValidUser( userName, userLength )) { //keep prompting till they get the point
				userName = prompt(userName +" was invalid.\nPlease enter a value from the list.\n\n" +uPrompt, "");
			}//end while: now they've given one of the valid user names
			strUserCookie = userName; //it'll be in the cookie next time: set in isValidUser
		} else { // there is a user cookie

		}//end if-else

		var startLink = strFormPageLink + thisUserStart + linkStyle + thisUserStart + "</a>";
		var strLastIssueCookie 	= "";
		if (! getCookie('lastIssueProcessed') ) {
			strLastIssueCookie = "no issue";
		} else {
			strLastIssueCookie = getCookie('lastIssueProcessed');
		}

		var lastLink 	= strFormPageLink + strLastIssueCookie  + linkStyle + strLastIssueCookie + "</a>";
		//alert("startLink = \n\t" +startLink+ "\nlastLink = \n\t" +lastLink );
		var topNotification = 'you are: <strong>' +strUserCookie+ '</strong>, ';
			topNotification += 'who starts at issue: ' +startLink+ '; last processed: ' +lastLink+ ' ';
			topNotification += '[<a href="#readMe">more</a>]<br/>';

		$("#topNotification").html(topNotification);

			//now compose output for notifications in #readMe
		var startIntro = "starts at issue_id: ";
		var lastProcessedIntro = " last processed: ";
		var u0startLink = startIntro + strFormPageLink + user0starts + linkStyle + user0starts + "</a>";
			$("#user0starts").html(u0startLink);
		var u1startLink = startIntro + strFormPageLink + user1starts + linkStyle + user1starts + "</a>";
			$("#user1starts").html(u1startLink);
		var u2startLink = startIntro + strFormPageLink + user2starts + linkStyle + user2starts + "</a>";
			$("#user2starts").html(u2startLink);
		var u0endLink = "";
		var u1endLink = "";
		var u2endLink = "";
		switch (strUserCookie){ //only have cookie for this machine so can't tell last processed for all users
			case "user0" :
				u0endLink = lastProcessedIntro + strFormPageLink + strLastIssueCookie + linkStyle + strLastIssueCookie + "</a>";
				$("#user0ended").removeClass("unimportant");
				$("#user0ended").html(u0endLink);
				break;
			case "user1" :
				u1endLink = lastProcessedIntro + strFormPageLink + strLastIssueCookie + linkStyle + strLastIssueCookie + "</a>";
				$("#user1ended").removeClass("unimportant");
				$("#user1ended").html(u1endLink);
				break;
			case "user2" :
				u2endLink = lastProcessedIntro + strFormPageLink + strLastIssueCookie + linkStyle + strLastIssueCookie + "</a>";
				$("#user2ended").removeClass("unimportant");
				$("#user2ended").html(u2endLink);
				break;
			default:
				break;
		}//end switch

	});//end doc.ready func

</script>

<span id="topNotification">
	this is topNotification <!--content gets replaced by javascript above-->
</span><!--end #topNotification-->

<!--end include userTracking.php-->

