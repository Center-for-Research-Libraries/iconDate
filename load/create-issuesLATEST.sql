SELECT * FROM issuesNEW 
ORDER BY issue_date 
INTO OUTFILE '/tmp/mysql/issuesLATEST.txt' 
FIELDS TERMINATED BY '|' 
LINES TERMINATED BY '\n';