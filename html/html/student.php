<?php


//set up error handling, can turn off once in production mode
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//phpinfo();
require_once("functions.php");
addCSS();

$pdo = connect_to_psql('gunsnrosesproject', $verbose=TRUE);

//retrieve username value from header
$query_string = ($_SERVER['QUERY_STRING']);
$almost = explode("=", $query_string);
$username = $almost[1];

//basic layout of login page
//PLEASE CLEAN THIS UP LATER ON
$html = '<html>';
$html .= '<head>';
$html .= '<title>ACME Testing Center Database</title>';
$html .= '</head>';
$html .= '<body>';
$html .= '<h1>Student Screen</h1>';
$html .= '<h3>Welcome ' . $username . ' !</h3>';
$html .= '<a href="student_schedule.php">Student Schedule</a><br />';
$html .= '<a href="student_records.php">Student Records</a>';
$html .= '<br><br><br><br><br>';
$html .= '<a href="index.php">Return to login page</a>';
$html .= '</html>';

echo $html;

//reset session variable
/*session_start();
echo $_SESSION["username"];
echo $_SESSION["id"];*/

?>
