<?php
// The name for the conference where yacomas is used

$CFG->conference_name="My Conference Name";

// Link for the conference 
//
$CFG->conference_link="http://random.conference.site";

// wwwroot, could be relative or absolute url
//
//$CFG->wwwroot = 'http://yacomas.conference.site';

$CFG->wwwroot = '/yacomas-rho';

//
// db settings
//

$CFG->dbname='yacomas';
$CFG->dbuser='yacomas';
$CFG->dbpass='yacomas';
$CFG->dbhost='localhost';


// The place where the files from the speakers will be stored   
// The directory must be created and give the specific permissions in order to apache can write inside that directory

$CFG->files = "/home/rolando/www/yacomas-documentos/";

// The mail to provide users who have PROBLEMS, WARNINGS with Yacomas.

$CFG->adminmail='admin@yourdomain';

// The mail to provide to all the users, and the mail that will be used to send mails for New account or reset account.

$CFG->general_mail='noreply@yourdomain';

// debug:
//      0 for production sites
//      7 for normal operation 
//      2047 for full debugging 

$CFG->debug = 2047;

// Workshops max limit to be used 

$CFG->limite=100;

// Start and End time for the events 24hrs format 

$CFG->def_hora_ini=8;
$CFG->def_hora_fin=22;

// Max of inscription to workshops and tutorials 

$CFG->max_inscripcionTA=2;
$CFG->max_inscripcionTU=3;

///////
//Mail stuff
///////

// The mail that should be used for send emails IP or domain name is valid

$CFG->smtp="your.smpt.domain";

// This constant will be used to enable or disable the feature to send mails(spam?) patux@patux.net

define (SEND_MAIL,0); // Disabled by default  (0 Disable, 1 Enable)

/******
 * CONSTANTES DE ENTORNO
 */
//Para index.php defino el estado 1 como nuevo asistente
define (NASISTENTE,1);
//para asistente/asistente.php defino los enteros por su estado
define (MASISTENTE,1);
define (LEVENTOS,2);
define (LTALLERES,3);
define (LTALLERESREG,4);
define (ENCUESTA,5);
define (HOJAREGISTRO,6);
// Para index.php defino el estado 1 como nuevo ponente
define (NPONENTE, 1);
// Para ponente/ponente.php
define (NPONENCIA,1);
define (PROPUESTAENV,2);
define (MPONENTE,3);
// Nomenclatura de ficheros
define (CARACTERSEPARADOR,"-"); //Cada fichero tendrá la siguiente nomenclatura
					   // <ruta><idusuario><CARACTERSEPARADOR><nombrefichero>
					   // Ejemplo /var/www/yacomas/documentos/1-prueba.pdf

?>
