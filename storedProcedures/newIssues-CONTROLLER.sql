/***************************************************************************************

AJE 2012-05-17

this is
newIssues-CONTROLLER.sql
	script file that calls createNewIssue,
	has some commands for checking results,
	and resetting for new test
******************************************
BEFORE using this file
1) run newIssues-SOURCErecords.sql
2) choose a combo of pub_id and rawIssueData returned there (choose the pub_ids)
3) change newIssues-BUFFERtable.sql:
	- set the WHERE clause to use the pub_ids chosen in step 1
4) upload and run newIssues-BUFFERtable on the server
	- now the source records are safe to mess with in table issuesBUFFER
	- issuesBUFFER.issue_id == issues.id
******************************************
5) pick an issue_id to start from
	SELECT * FROM issuesBUFFER WHERE issue_id = [your target id];
6) copy its rawIssueData, or just observe it in the output
7) use that rawIssueData to construct statements that will
    CALL createNewIssue(
        [issuesBUFFER.issue_id],
        '[first issue yyyy-mm-dd]',
        '[last issue yyyy-mm-dd]'
    );
    put them in a SQL file if there are more than a few ranges.
    /var/www/html/mySQL/ISSUES/batch/[issue_id].sql
        so like:
    /var/www/html/mySQL/ISSUES/batch/2012May24.sql
******************************************
8) run the new statements
    8A) from MySQL Workbench or
    8B) from mysql console as
            mysqld> source /var/www/html/mySQL/ISSUES/batch/[issue_id].sql
******************************************

AMENDMENTS IN THIS SECTION TO ACCOMODATE STUDENT WORK VIA http://192.168.1.195/iconDate/index.php?issue_id=[id]

9) MARK THE EXISTING ISSUES AS HAVING HAD THEIR CHILDREN CREATED
	(new fields exist to hold this)
		specificIssuesCreated, tinyint(1), YES, , 0,
		specificIssuesCreatedDate, date, YES, , 0000-00-00,
9A) STATEMENTS IN newIssues-LOAD.sql
    - get issue_ids (parent records with rawIssueData in original 'issues' table) from issuesBUFFER
    - for those issue_ids in issues: SET specificIssuesCreated = 1, specificIssuesCreatedDate = CURRENT_DATE()
    - now the original issues are marked
9B) newIssues-LOAD.sql continues
    put the new child records into outfile
9C) COPY + MODIFY the outfile (remove issue_ids)
    9C1) SHELL: COPY - scripts are detailed in newIssues-LOAD.sql
    9C2) UESTUDIO: DOWNLOAD, OPEN the outfile
    9C3) REPLACE all issue_ids with 0 (so they get autonumbered when we load)
        UEDit syntax for regex:
            ^\d+\|
        replace with
            0\|
    9C4) UPLOAD the new file

10) newIssues-LOAD.sql continues
    LOAD the new records into issues table
        LOAD DATA LOCAL INFILE '/var/www/html/datasources/mySQL/ISSUES/issuesLOAD[dateTime].txt'
        INTO TABLE issues
        FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';

******************************************
notes on specific pub_ids/issue_ids:
- pub_id: 00245964 is nice
- issue_id: 36665821, pub_id '00245964'
(DLC holds, but rawIssueData is weird;
AQM holds, and at some point in 1813 there's a 3-day gap btw pattern dates + AQM dates)
- issue_id 36634666 is awesome; lots of readable rawIssueData, lots of new issues
***************************************************************************************/


/* step 5) pick an issue_id to start from, copy issue_id, rawIssueData */
SELECT pub_id, issue_id, rawIssueData FROM issuesBUFFER
    WHERE
    issuesBUFFER.rawIssueData <> ""
    AND issuesBUFFER.rawIssueData <> '0000-00-00'
    AND issuesBUFFER.rawIssueData <> '...'
    AND issuesBUFFER.rawIssueData NOT LIKE '%etain%'
    AND issuesBUFFER.rawIssueData NOT LIKE 'v%'
    AND issuesBUFFER.rawIssueData NOT LIKE 'n%'
    AND issuesBUFFER.rawIssueData NOT LIKE 'c%'
    AND issuesBUFFER.rawIssueData NOT LIKE '[%'
    AND issuesBUFFER.rawIssueData NOT LIKE '%-'
    AND issuesBUFFER.rawIssueData NOT LIKE 'new ser%'
    AND issuesBUFFER.rawIssueData NOT LIKE 'Micro%'
    /* AND issuesBUFFER.rawIssueData NOT REGEXP '1-' */

    AND issuesBUFFER.specificIssuesCreated = 0 AND issuesBUFFER.specificIssuesCreatedDate = '0000-00-00'
    AND issuesBUFFER.repos_id <> 'AQM' /* Antiquarians are all set */
ORDER BY pub_id, rawIssueData, issue_id;

/* get specific issuesBUFFER.rawData to create CALL statements with */
SELECT issue_id, rawIssueData FROM issuesBUFFER WHERE issue_id = [your target id];

/* put these calls in a new script file: */
CALL createNewIssue([issuesBUFFER.issue_id], '[first issue yyyy-mm-dd]', '[last issue yyyy-mm-dd]');


/* verify operation */
SELECT issue_id, pub_id, issue_date, repos_id, format, update_date, issue_note,
    specificIssuesCreated, specificIssuesCreatedDate
FROM issuesNEW ORDER BY issue_date;


/* CAREFUL! reset for new test */
DELETE * FROM issuesNEW WHERE 1 > 0;

/* random query saved */
SELECT issuesNEW.issue_date AS newIMPORTdate, issuesBUFFER.issue_date
FROM issuesNEW
    LEFT OUTER JOIN issuesBUFFER
    ON issuesNEW.issue_date = issuesBUFFER.issue_date
ORDER BY issuesNEW.issue_date, issuesBUFFER.issue_date;