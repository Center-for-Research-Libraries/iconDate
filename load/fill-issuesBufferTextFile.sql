SELECT * FROM issues
WHERE issue_id IN (
    /* GET THESE ids via newIssues-SOURCErecords.sql,
    then cull that data in UEStudio; here we just need ids */


)
   /* AND repos_id <> 'AQM' */
ORDER BY issue_date
INTO OUTFILE '/tmp/mysql/issuesBUFFER.txt'
FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';

