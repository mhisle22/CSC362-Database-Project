<?php


//set up error handling, can turn off once in production mode
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//phpinfo();
require_once("functions.php");

function display_upcoming($pdo)
{
	
 
	$sql ='SELECT * FROM upcoming_instructors';
	
	try
	{
		$stmt = $pdo->query($sql);
	//	var_dump($stmt);
	}
	catch(\PDOException $e)
	{
		throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}
 
 

 	 

	echo '<h1>Upcoming Tests:</h1>';
	echo '<form method="post">' . "\n";

	//headers and columns
	$labels = ['Name', 'Date', 'Start Time', 'Test Length', 'Test Status'];
	$cols = ['student_name', 'test_date', 'test_start_time', 'test_length', 'test_status'];

	echo "\n<table>\n";

	//create the headers in a row
	echo'<tr>';
	foreach($labels as $th)
	{
		echo '<th>' . $th . '</th>';
	}
	echo "</tr>\n";
	
	//add data to table
//	var_dump($stmt);
	foreach ($stmt as $row)
	{
	//	var_dump($row);
		echo "<tr>\n";
		
		$id = $row[$cols[0]];
		echo "<td>" . $id . "</td>\n";
	//	var_dump($id);
		foreach (array_slice($cols, 1) as $col)
		{
			$td = $row[$col];
			echo '<td>' . $td . "</td>\n";
		}
		echo "</tr>\n";
	};
	echo "</table>\n\n";
	echo "<br/>";
	echo "<a href='create_test.php'>Create Test</a><br />";
	echo "<br/>";
	echo "<a href='instructors_test_log.php'>Test Log</a><br />";
	echo "</form>\n";
 
}
//function handles isset[] and creates the html for inputing data
/*
function createTest($pdo)
{
	$html = <<<_HTML_
	<h1>Create New Test</h1>
	<form method='post'>
	<table>
	<tr><td>Instructor ID</td><td><input type='text' name='instructor_id' /></td></tr>
	<tr><td>Student ID</td><td><input type='text' name='student_id' /></td></tr>
	<tr><td>Test Date</td><td><input type='text' name='test_date' value='ex: mm/dd/yyyy' /></td></tr>
	<tr><td>Test Start Time</td><td><input type='text' name='test_start_time' value='ex: 10:00' /></td></tr>
	<tr><td>Test Length</td><td><input type='text' name='test_length' value='ex:  minutes' /></td></tr>
	<tr><td>Test Version</td><td><input type='text' name='test_version' value='ex A' /></td></tr>
	<tr><td>Course</td><td><input type='text' name='test_course' value='ex: CSC 220' /></td></tr>
	<tr><td>File</td><td><input type='file' name='test_blob' /> </td></tr>
	<tr><td><button type='submitTest' value='submitTest'>Create Test</button></td></tr>
	</table>
	</form>	
_HTML_;
echo $html;
}
 */
//function hands isset[] and creates the html
/*
function testLog($pdo)
{
	//to create test log table, would reuse a lot of same code from populating upcoming tests table
	$html = <<<_HTML_
	<h1>Test Log</h1>
	<form method="post">
	<div>
	<button type='submit' value='month'>Month</button>
	<button type='submit' value='week'>Week</button>
	<button type='submit' value='year'>Year</button>
	</div>

_HTML_;
	echo $html;

}
*/
function main()
{

	$pdo = connect_to_psql('gunsnrosesproject', $verbose=TRUE);
	
	//get the user info from header
/*	$query_string = ($_SERVER['QUERY_STRING']);
	$almost = explode("=", $query_string);
	//	var_dump($almost);
	$username = $almost[1];*/
	session_start();
	$username = $_SESSION['username'];
	$id = $_SESSION['id'];
	echo 'username: ';
	echo $username;
	echo 'id: ';
	echo $id;
	//basic layout of login page
	$html = '<html>';
	$html .= '<head>';
	$html .= '<title>ACME Testing Center Database</title>';
	$html .= '</head>';
	$html .= '<p>logged in as ' . $username . '</p>';
	$html .= '<body>';
	$html .= '</body>';
	$html .= '</html>';
	echo $html;
	display_upcoming($pdo);
//	createTest($pdo);
//	testLog($pdo);

}
main();
?>
