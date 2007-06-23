<?php
/*******************************************/
/*** Changes this settings to your needs ***/
/*******************************************/

//
// db settings
//

$CFG->dbname='yupana';
$CFG->dbuser='yupana';
$CFG->dbpass='yupana';
$CFG->dbhost='localhost';

// The place where the files from the speakers will be stored   
// The directory must be created and give the specific permissions in order to the webserver can write inside that directory

$CFG->files = '/full/path/to/archives';

/*******************************************/
/*******************************************/
/*******************************************/

// Set wwwroot if had problems on page style or links
// without trailing slash
// $CFG->wwwroot = 'http://servername/yupana';
?>
