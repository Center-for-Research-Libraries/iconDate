LOAD DATA LOCAL INFILE '/tmp/mysql/issuesGOODtoLOAD.txt' 
INTO TABLE issues 
FIELDS TERMINATED BY '|' 
LINES TERMINATED BY '\n';