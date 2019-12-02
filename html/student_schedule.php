<?php


//set up error handling, can turn off once in production mode
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//phpinfo();
require_once("functions.php");


$pdo = connect_to_psql('gunsnrosesproject', $verbose=TRUE);


//basic layout of login page
$html = '<html>';
$html .= '<head>';
$html .= '<title>ACME Testing Center Database</title>';
$html .= '</head>';
$html .= '<body>';
$html .= '<h1>Student Schedule</h1>';
$html .= '</html>';

echo $html;


?>
