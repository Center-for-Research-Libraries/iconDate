/***************************************************************************************

This file needs to run directly on the server
    - DROP + CREATE TABLE statements will work
    - SELECT INTO OUTFILE and LOAD LOCAL INFILE will fail for permissions
***************************************************************************************/

DESCRIBE issues;
/* MAKE SURE THE SCHEMA BELOW MATCHES THE REAL issues TABLE */

DROP TABLE IF EXISTS `issuesBUFFER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `issuesBUFFER` (
  `issue_id`                    int(10) unsigned NOT NULL,
  `pub_id`                      varchar(15),
  `issue_date`                  date DEFAULT NULL,
  `rawIssueData`                text,
  `repos_id`                    varchar(5) DEFAULT NULL,
  `condition_id`                tinyint(3) unsigned DEFAULT NULL,
  `format`                      tinytext,
  `format_id`                   tinyint(3) unsigned DEFAULT NULL,
  `archive_status_id`           tinyint(3) unsigned DEFAULT NULL,
  `provenance`                  text,
  `update_date`                 date DEFAULT NULL,
  `issue_note`                  text,
  `specificIssuesCreated`       TINYINT(1) UNSIGNED DEFAULT 0,
  `specificIssuesCreatedDate`   DATE DEFAULT '0000-00-00',
  PRIMARY KEY (`issue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


/* ADD THE REAL PUB_ID TO THE WHERE CLAUSE */

SELECT * FROM issues
WHERE issue_id IN (
    /* ALREADY GOT: we did this work in newIssues-SOURCErecords.sql,
    then culled that data in UEStudio... see issue_ids-forWebForm.csv */
)
    AND repos_id <> 'AQM'
ORDER BY issue_date
INTO OUTFILE '/tmp/mysql/issuesBUFFER.txt'
FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';


LOAD DATA LOCAL INFILE '/tmp/mysql/issuesBUFFER.txt'
INTO TABLE issuesBUFFER
FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';

SELECT * FROM issuesBUFFER;
