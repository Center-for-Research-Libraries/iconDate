/***************************************************************************************
AJE 2012-05-17
    newIssues-INSERT.sql
    	contains the procedures to build and insert a new issue record
    	into the issuesNew table,
    	making it ready for future load into the real 'issues ' table.
    main procedure is createNewIssue()
    called from SQL in newIssues-CONTROLLER.sql
***************************************************************************************/

/***************************************************************************************
    setDateSpread fills dateSpread var, which is number of days between two dates
***************************************************************************************/
DROP PROCEDURE IF EXISTS setDateSpread;
DELIMITER |
CREATE PROCEDURE setDateSpread(
    IN      myFreqID    TINYTEXT,
    INOUT   dateSpread   INT
)
    BEGIN
        CASE myFreqID
            WHEN "d" THEN   SET dateSpread = 1;
            WHEN "w" THEN   SET dateSpread = 7;
            WHEN "e" THEN   SET dateSpread = 14;
            WHEN "m" THEN   SET dateSpread = 30;
            WHEN "b" THEN   SET dateSpread = 60;
            WHEN "q" THEN   SET dateSpread = 90;
            WHEN "a" THEN   SET dateSpread = 365;
            WHEN "g" THEN   SET dateSpread = 730;
            WHEN "h" THEN   SET dateSpread = 2190;
            ELSE            SET dateSpread = -9999;
        END CASE;
    END
|
DELIMITER ;


/***************************************************************************************
    setMyIssueDate calculates next date of an issue in the series, based on freq_id_code from publications table
***************************************************************************************/
DROP PROCEDURE IF EXISTS setMyIssueDate;
DELIMITER |
CREATE PROCEDURE setMyIssueDate(
    IN myFreqID TINYTEXT,
    IN issueDateSeed DATE,
    INOUT myIssueDate DATE
)
    BEGIN
    CASE myFreqID
            WHEN "d" THEN SELECT DATE_ADD( issueDateSeed, INTERVAL 1 DAY )   INTO myIssueDate;
            WHEN "w" THEN SELECT DATE_ADD( issueDateSeed, INTERVAL 1 WEEK )  INTO myIssueDate;
            WHEN "e" THEN SELECT DATE_ADD( issueDateSeed, INTERVAL 2 WEEK )  INTO myIssueDate;
            WHEN "m" THEN SELECT DATE_ADD( issueDateSeed, INTERVAL 1 MONTH ) INTO myIssueDate;
            WHEN "b" THEN SELECT DATE_ADD( issueDateSeed, INTERVAL 2 MONTH ) INTO myIssueDate;
            WHEN "q" THEN SELECT DATE_ADD( issueDateSeed, INTERVAL 3 MONTH ) INTO myIssueDate;
            WHEN "a" THEN SELECT DATE_ADD( issueDateSeed, INTERVAL 1 YEAR )  INTO myIssueDate;
            WHEN "g" THEN SELECT DATE_ADD( issueDateSeed, INTERVAL 2 YEAR )  INTO myIssueDate;
            WHEN "h" THEN SELECT DATE_ADD( issueDateSeed, INTERVAL 3 YEAR )  INTO myIssueDate;
            ELSE SET myIssuedate = '0000-00-00';
        END CASE;
    END
|
DELIMITER ;


/***************************************************************************************
    insertThisIssue creates new record for the date passed in; just reduces clutter in the main function
***************************************************************************************/
DROP PROCEDURE IF EXISTS insertThisIssue;
DELIMITER |
CREATE PROCEDURE insertThisIssue(
        IN myPubID TINYTEXT, IN myIssueDate DATE,
        /* IN myRawIssueData TEXT, */
        IN myReposID TINYTEXT,
        IN myConditionID TINYTEXT, IN myFormat TINYTEXT, IN myFormatID TINYTEXT, IN myArchiveID TINYTEXT, IN myIssueNote TINYTEXT
)
    BEGIN
        DECLARE output TEXT;
        SET output = "this is insertThisIssue()";
        /* SELECT output; */

/*INSERT INTO issuesNEW*/
        INSERT INTO issuesNEW
            SET
            pub_id      = myPubID,      issue_date  = myIssueDate,
            /* rawIssueData = myRawIssueData, CANCEL THIS: NOT saving existing rawIssueData into new records */
            rawIssueData = '', repos_id    = myReposID,    condition_id = myConditionID,
            format = myFormat, format_id = myFormatID,
            archive_status_id = myArchiveID, provenance = '',     update_date = CURRENT_DATE(),
            issue_note  = myIssueNote, specificIssuesCreated = 1, specificIssuesCreatedDate = CURRENT_DATE();
    END
|
DELIMITER ;


/***************************************************************************************
    createNewIssue
        the main function
***************************************************************************************/
DROP PROCEDURE IF EXISTS createNewIssue;
DELIMITER |
CREATE PROCEDURE createNewIssue(
    IN  input_issue_id INT UNSIGNED,
    IN  issueDateSeed DATE,
    IN  lastIssueDate DATE
)
    BEGIN
                /* data from existing fields in mother record */
        DECLARE myPubID, myReposID, myConditionID, myFormat, myFormatID, myArchiveID, myIssueNote TINYTEXT;
        DECLARE myRawIssueData TEXT; /* from mother record, but larger data size */

        DECLARE myFreqID        TINYTEXT;   /* get freq_id_code from publications, below */
        DECLARE myIssueDate     DATE;       /* new specific date for issue_date field */

        DECLARE dateDiff        INT;        /* num days btw most recent issue added, and next  */
        DECLARE dateSpread      INT;        /* num days required to keep looping below */
        DECLARE loopCtr         INT;        /* num passes thru loop */

        DECLARE new_issue_id INT UNSIGNED;

        DECLARE strDebug        TINYTEXT;   /* holds debugging info */

    /************* end DECLARE, only now can we SET *************/

    /* set values specific to this record */
        SET    myIssueDate = '0000-00-00'; /* real data for myIssueDate filled in by setMyIssueDate */
    /* use the existing values for most of the INSERT */

        SELECT pub_id               INTO myPubID        FROM issuesBUFFER WHERE issue_id = input_issue_id;
        /* NOT saving existing rawIssueData into new records
        SELECT rawIssueData         INTO myRawIssueData FROM issuesBUFFER WHERE issue_id = input_issue_id; */
        SELECT repos_id             INTO myReposID      FROM issuesBUFFER WHERE issue_id = input_issue_id;
        SELECT condition_id         INTO myConditionID  FROM issuesBUFFER WHERE issue_id = input_issue_id;
        SELECT format               INTO myFormat       FROM issuesBUFFER WHERE issue_id = input_issue_id;
        SELECT format_id            INTO myFormatID     FROM issuesBUFFER WHERE issue_id = input_issue_id;
        SELECT archive_status_id    INTO myArchiveID    FROM issuesBUFFER WHERE issue_id = input_issue_id;
            /* myIssueNote in 2 steps, IF is just to add separator if there was data */
        SELECT issue_note           INTO myIssueNote    FROM issuesBUFFER WHERE issue_id = input_issue_id;
        IF myIssueNote = "" OR myIssueNote IS NULL THEN /* was empty */
            SET    myIssueNote = CONCAT( myIssueNote, 'via CRL db procedure (issues.', input_issue_id, ')');
        ELSE /* add separator */
            SET    myIssueNote = CONCAT( myIssueNote, '; via CRL db procedure (issues.', input_issue_id, ')');
        END IF;



        SELECT freq_id_code INTO myFreqID FROM publications
            WHERE pub_id IN (
                SELECT pub_id FROM issuesBUFFER WHERE issue_id = input_issue_id
            );
        SET strDebug =  CONCAT( "myFreqID='", myFreqID, "'; ");

        CALL insertThisIssue( /* use values already set to insert record for date that was passed in */
            myPubID, issueDateSeed, myReposID, myConditionID, myFormat, myFormatID, myArchiveID, myIssueNote
        );

    /* call helper procedures to set vars before WHILE loop */
            /* advance the issue date selection; already inserted record for date that was passed in */
        CALL setMyIssueDate(myFreqID, issueDateSeed, myIssueDate);
        SET strDebug =  CONCAT( strDebug, "; setMyIssueDate returned '", myIssueDate, "'; ");

        CALL setDateSpread(myFreqID, dateSpread);
        SET strDebug = CONCAT( strDebug, "dateSpread=" , dateSpread, "; ");

        SET dateDiff = DATEDIFF( lastIssueDate, issueDateSeed );
        SET strDebug = CONCAT( strDebug, "dateDiff btw ", lastIssueDate, " and ", issueDateSeed, " = " , dateDiff, "; ");

/* SELECT strDebug; */

        SET loopCtr = 0;
            /* IN WHILE:
                set myIssueDate to be issue_date for the new record, insert new record.
                reset condition at bottom of loop
            */
        WHILE (dateDiff >= dateSpread) DO
            SET strDebug = CONCAT( "HIT THE WHILE: ", myPubID, ".fq:", myFreqID, ".", loopCtr, ") dateDiff '", lastIssueDate, "'-'", issueDateSeed, "'=" , dateDiff, "; ");
/* SELECT strDebug; */
            CALL setMyIssueDate(myFreqID, issueDateSeed, myIssueDate );

            CALL insertThisIssue(
                /* provenance, updateDate, specificIssuesCreated, specificIssuesCreatedDate are not passed
                    - now pass myIssueDate instead of issueDateSeed like we did before loop */
                myPubID, myIssueDate, myReposID, myConditionID, myFormat, myFormatID, myArchiveID, myIssueNote
            );

            /* prepare for next pass thru loop */
            SET issueDateSeed = myIssueDate;
            SET loopCtr = loopCtr +1;
                /* loop exit condition depends on this */
            SET dateDiff = DATEDIFF( lastIssueDate, issueDateSeed );

                /* just debugging */
            SET strDebug = CONCAT( strDebug, "end loop, dateDiff '", issueDateSeed, "'=" , dateDiff);
            IF (dateDiff >= dateSpread) THEN
                SET strDebug = CONCAT( strDebug, " do loop");
            ELSE
                SET strDebug = CONCAT( strDebug, " STOP LOOP");
            END IF;


/* next is awesome for console debugging:
        IS NOT A RESULT SET + MAKES PHP UNABLE TO GIVE USER FEEDBACK
        SELECT strDebug;
*/

/* trying to give PHP something */
SELECT MAX(issue_id) FROM issuesNEW AS newestID;

       END WHILE;

    /* get the new ID */
     SELECT MAX(issue_id) FROM issuesNEW AS latestID;
   /* SELECT * FROM issuesNEW WHERE issue_id = MAX(issue_id);
        Error Code: 1111. Invalid use of group function     */


     END
|
DELIMITER ;