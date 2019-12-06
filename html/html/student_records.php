<?php


//set up error handling, can turn off once in production mode
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//phpinfo();
require_once("functions.php");
addCSS();

$pdo = connect_to_psql('gunsnrosesproject', $verbose=TRUE);


//basic layout of login page
$html = '<html>';
$html .= '<head>';
$html .= '<title>ACME Testing Center Database</title>';
$html .= '</head>';
$html .= '<body>';
$html .= '<h1>Student Records</h1>';
$html .= '</html>';

echo $html;


//retrieve session variables
session_start();
$username = $_SESSION["username"];
$id = $_SESSION["id"];


//retrieve number of elements displayed
$numRecords = NULL;
if(isset($_POST['numElements']))
{
    $numRecords = $_POST['numElements'];
}
else {
    $numRecords = 5;
}



//retrieve name of student so the header looks nice
//along with extra_time data while we're at it
$sql = 'SELECT student_first_name, student_last_name, student_extra_time';
$sql .= ' FROM students';
$sql .= " WHERE student_id = '{$id}'";

$output = NULL;
try {
	$output = $pdo->query($sql);
	//debug_message('Query successful');
} catch (\PDOException $e) {
	debug_message('ERROR: Query failed:');
	debug_message('PSQL Error Message: ' . $e);
}

$hasExtraTime = NULL;

foreach($output as $row)
{
	echo '<h2>Previous Tests of ' . $row['student_first_name'] . ' ' .
	$row['student_last_name'] . '</h2>';
	$hasExtraTime = $row['student_extra_time'];
}

// Query to get the schedule data for the student
$sql = 'SELECT test_time_stamp, instructor_last_name, test_course, test_length, test_id';
$sql .= ' FROM reservations';
$sql .= ' NATURAL JOIN tests';
$sql .= ' NATURAL JOIN instructors';
$sql .= " WHERE student_id = '{$id}'";
$sql .= " ORDER BY test_time_stamp DESC;";

//debug_message('$sql = ' . $sql);

//send the query
$output = NULL;
try {
	$output = $pdo->query($sql);
	//debug_message('Query successful');
} catch (\PDOException $e) {
	debug_message('ERROR: Query failed:');
	debug_message('PSQL Error Message: ' . $e);
}


echo "<table>\n";
echo "<tr><th>Test Date</th><th>Test Time</th><th>Instructor</th><th>Course</th><th>Length</th></tr>";

$iteration = 0;
foreach($output as $row)
{
	//limit it based on limiter
	$iteration++;
	if($numRecords != 'any'&& $iteration > $numRecords+5)
	{
		break;
	}

	//translation: if day in the past...
	if(strtotime($row['test_time_stamp']) < strtotime('now'))
	{
	// a few of these variables need some formating first
	$timestamp = explode(" ", $row['test_time_stamp']);
	//modify length if extra time is needed
	if($hasExtraTime) {	
		$lengthStamp = strtotime($row['test_length']) + 60*60*2; //add two hours
		$length = date('h:i:s', $lengthStamp);
	}
	else {	
		$lengthStamp = strtotime($row['test_length']); 
		$length = date('h:i:s', $lengthStamp);
	}

	echo "<tr>";
	echo '<td>' . $timestamp[0]  . '</td>';
	echo '<td>' . $timestamp[1] . '</td>';
	echo '<td>' . 'Dr. '  . $row['instructor_last_name']  . '</td>';
	echo '<td>' . $row['test_course'] . '</td>';
	echo '<td>' . $length . '</td>';

	echo '</tr>';
	}
}

echo "</table>";

//create limiter in case students has many tests
echo "<br><p>Number of records displayed</p>";
echo "<form method='post'>";
echo "<select name='numElements'>";

//set default based on current value
if($numRecords == 5) {
echo "<option selected value='5'>5</option>";
echo "<option value='10'>10</option>";
echo "<option value='50'>50</option>";
echo "<option value='any'>Any</option>";
}
elseif($numRecords == 10) {
echo "<option value='5'>5</option>";
echo "<option selected value='10'>10</option>";
echo "<option value='50'>50</option>";
echo "<option value='any'>Any</option>";
}
elseif($numRecords == 50){
echo "<option value='5'>5</option>";
echo "<option value='10'>10</option>";
echo "<option selected value='50'>50</option>";
echo "<option value='any'>Any</option>";
}
else {
echo "<option value='5'>5</option>";
echo "<option value='10'>10</option>";
echo "<option value='50'>50</option>";
echo "<option selected value='any'>Any</option>";
}

echo "</select>";
echo "<br><br><input type='submit' value='Refresh'>";
echo "</form>";

returnButton();

?>
