/*

this file documents how to take issuesNEW records
	(created by students via php/javascript interface)
	and put them into the real issues table.

do this before going to newIssues-CONTROLLER.sql
	and setting up the next batch of publications records for students to work.

	get issues_id of the original issues (parent records with rawIssueData) from issuesNEW
*/

SELECT  /* issue_id, issue_note, */
        DISTINCT REPLACE(REPLACE(issue_note, 'via CRL db procedure (issues.', ''), ')', '')
        AS parent_issue_id
FROM issuesNEW
ORDER BY parent_issue_id;

/*
	 -take those issue_ids from issuesBUFFER
		and go to the REAL ISSUES TABLE
	- MARK ORIGINAL ISSUES AS HAVING HAD THEIR CHILD ISSUE RECORDS CREATED:
	FIELDS:
		- specificIssuesCreated, tinyint(1), YES, , 0,
		- specificIssuesCreatedDate, date, YES, , 0000-00-00,
 */

/* TEST IT WITH SELECT 1ST !!!! */
SELECT issue_id, rawIssueData, pub_id, issue_note, specificIssuesCreated, specificIssuesCreatedDate
FROM issues
WHERE issue_id
    IN ();

/*  PERFORM THE UPDATE */
UPDATE issues
SET specificIssuesCreated = 1, specificIssuesCreatedDate = CURRENT_DATE()
WHERE issue_idPREVENTMISTAKE
    IN ();


/* NOW THE issues PARENT RECORDS HAVE BEEN MARKED:
    - PUT THE CHILD RECORDS INTO OUTFILE
    - must be run from console:
    mysqld> source /var/www/html/datasources/mySQL/ISSUES/load/create-issuesLATEST.sql
        contains 1 statement, but putting it there is easier
        SELECT * FROM issuesNEW ORDER BY issue_date INTO OUTFILE '/tmp/mysql/issuesLATEST.txt' FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';


COPY THE NEW FILE TO datasources working directory:
    RUN SHELL SCRIPT
        bash /var/www/html/datasources/mySQL/ISSUES/load/issuesCOPY.sh
    contents are:
        system sudo chown aelliott /tmp/mysql/issuesLATEST.txt
        system sudo chmod 675 /tmp/mysql/issuesLATEST.txt
        system sudo mv /tmp/mysql/issuesLATEST.txt /var/www/html/datasources/mySQL/ISSUES/load

C) UESTUDIO: DOWNLOAD, OPEN the outfile
    C1) REPLACE all issue_ids with 0 (so they get autonumbered when we load)
				UEDit syntax for regex:
					^\d+\|
					replace with
					0\|

    C2) UPLOAD the new file
    C3) COPY it
        RUN SHELL SCRIPT
            bash /var/www/html/datasources/mySQL/ISSUES/load/issuesPREPload.sh
        contents are:
            cp /var/www/html/datasources/mySQL/ISSUES/load/issuesLATEST.txt /tmp/mysql/issuesGOODtoLOAD.txt
            cp /var/www/html/datasources/mySQL/ISSUES/load/issuesLATEST.txt /var/www/html/datasources/mySQL/ISSUES/load/old/issuesLATEST.txt

D) LOAD THE NEW RECORDS INTO issues table
    - must be run from console:
    mysqld> source /var/www/html/datasources/mySQL/ISSUES/load/load-issuesLATEST.sql
        contains 1 statement, but putting it there is easier
    LOAD DATA LOCAL INFILE '/tmp/mysql/issuesGOODtoLOAD.txt' INTO TABLE issues FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';

E) check the load    */
SELECT * FROM issues
WHERE issue_id IN (
    SELECT REPLACE(
        REPLACE(issue_note, 'via CRL db procedure (issues.', ''), ')', '')
    FROM issuesNEW
);



/* F) GET THE pub_ids TO BE REMOVED FROM issuesBUFFER */
SELECT DISTINCT(pub_id) FROM issuesNEW;

/* G) REMOVE THE PROCESSED ISSUES FROM issuesBUFFER
      G1)   reformat the IN clause and paste below */
SELECT * FROM issuesBUFFER WHERE pub_id IN ('fake-ids'); /* TEST IT FIRST */
DELETE * FROM issuesBUFFER WHERE pub_id IN ('fake-ids'); /* REMOVE FROM issuesBUFFER when ready */

/* H) WIPE the issuesNEW table
    - bad asterix syntax to prevent errors below */
DELETE * FROM issuesNEW WHERE 1 > 0;

/* I) GET REMAINING pub_ids FOR THE JAVASCRIPT ARRAYS */
SELECT DISTINCT(pub_id) FROM issuesBUFFER; /* EXPORT THIS FILE to pub_ids-forWebForm.csv  */
SELECT issue_id FROM issuesBUFFER; /* EXPORT THIS FILE to issue_ids-forWebForm.csv */
/*
	put the contents of those files into clientSide_pub_ids.js and clientSide_issue_ids.js

	userTracking.php, uncomment the line 'alert(strDebug);' [ca. line 52] to see where each user starts

	2012-Nov-02
		userTracking.php has javascript that fills the following div/spans
		with the start and end positions in clientSide_issue_ids array for ea. user:
			- topNotification
			- user0starts [etc.]
			- user0ended [etc.] - this will be the cookie value from lastIssueProcessed
			and can be used if they forget/fail to bookmark the last issue.

*/






