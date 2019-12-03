<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("functions.php");

main();
$pdo = connect_to_psql('lab07', $verbose=TRUE);

/*$sql = 'SELECT * FROM report_students;';
$output = '';
try {
  $output = $pdo->query($sql);
  debug_message('Query succeeded');

} catch (\PDOException $e) {
  debug_message('PSQL query failed.');
  debug_message('Error message: ' . $e);
}*/

$output = getStudentReports($pdo);

echo "<h1>Club Members</h1>";

echo "\n<table style='border: 1px solid black;'>\n";
echo "<tr><th>Student Name<th>Dorm Name</th><th>Student's Ducks</th>
	<th>Delete</th><th>Edit</th></tr>\n";
foreach ($output as $row)
{
	echo "<tr>\n";
	echo "<td>" . $row['student_name'] . "</td>\n";
	echo "<td>" . $row['dorm_name'] . "</td>\n";
	echo "<td>" . $row['students_ducks'] . "</td>\n";
	echo "<td>Delete</td>\n";
	echo "<td>Edit</td>\n";
	echo "</tr>\n";
}
echo "</table>\n";
//debug_message("Table created successfully!");

echo "<form method='post' action='students.php'>";
echo "<br>First name: <input name='fname' type='text'><br>";
echo "Last name: <input name='lname' type='text'><br>";
echo "Dorm name: <input name='dorm' type='text'><br>";
echo "Duck: <input name='ducks' type='text'><br>";
echo "<input type='submit' name='add_student' value='Submit'>";
echo "</form>";


?>
