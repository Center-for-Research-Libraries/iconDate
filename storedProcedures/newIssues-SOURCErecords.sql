/*

    results saved into: C:\Andy\IMLS\datasources\mySQL\formatField-issue_date\distinct-pub_id-iss_date-rawIssD.csv
        - runs for 145 secs
        - returns 574909 rows [14 May 12; 12:15]

		Examine the returned data, in DB form or in text file,
			choose a combo of pub_id and rawIssueData to be processed using:
				- newIssues-BUFFERtable.sql (creates new tables for the
				- newIssues-CONTROLLER.sql
					(which uses calls procesdures in newRecord-INSERT.sql)
				-

*/

SELECT
    /* pub_id, COUNT(rawIssueData) */
    publications.freq_id_code, publications.formerFrequency321,
    issues.pub_id, issues.issue_date, issues.rawIssueData
FROM publications, issues
WHERE
    (issues.pub_id = publications.pub_id)
    /* AND (publications.freq_id_code = frequencies.freq_id_code) */
    AND publications.freq_id_code <> ""
    AND publications.freq_id_code <> 'u'
    AND publications.freq_id_code <> 'c' /* semiweekly not currently supported */
    AND publications.freq_id_code <> 's' /* semimonthly not currently supported */
    AND publications.freq_id_code <> 't' /* 'three times a year' not currently supported */
    AND publications.freq_id_code <> 'f' /* semiannual not currently supported */
    AND publications.freq_id_code <> 'k' /* 'Continuously updated' not currently supported */
    AND publications.freq_id_code <> 'z' /* 'Other' not currently supported */
    AND publications.formerFrequency321 = "" /* only doing current freq now */
    AND publications.numberingNote515   = "" /* skip dealing with these */
    AND issues.rawIssueData <> ""
    AND issues.rawIssueData <> '0000-00-00'
    AND issues.rawIssueData <> '...'
    AND issues.rawIssueData NOT LIKE '%etain%'
    AND issues.rawIssueData NOT LIKE 'v%'
    AND issues.rawIssueData NOT LIKE 'n%'
    AND issues.rawIssueData NOT LIKE 'c%'
    AND issues.rawIssueData NOT LIKE '[%'
    AND issues.rawIssueData NOT LIKE '%-'
    AND issues.rawIssueData NOT LIKE 'new ser%'
    AND issues.rawIssueData NOT LIKE 'Micro%'
    AND issues.rawIssueData REGEXP '[0-9]'                      /* must include at least 1 number */
    /* AND issues.rawIssueData NOT REGEXP '[0-9][0-9][0-9][0-9]'   /* not like just a year
        BUT THIS STRIP ALL WITH A YEAR */
    AND issues.rawIssueData NOT REGEXP '[0-9]*\-[0-9]'         /* not like just vols or years */
    /* AND issues.rawIssueData NOT REGEXP '[0-9]'                  /* not like just vol.
        BUT THIS STRIPS ALL WITH NUMBERS */


    AND issues.specificIssuesCreated = 0 AND issues.specificIssuesCreatedDate = '0000-00-00'
    AND issues.repos_id <> 'AQM' /* Antiquarians are all set */

ORDER BY issues.pub_id;