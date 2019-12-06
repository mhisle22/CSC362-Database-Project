<?php


//set up error handling, can turn off once in production mode
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//phpinfo();
require_once("functions.php");
addCSS();

function display_upcoming($pdo, $id)
{
	
 
	$sql ='SELECT * FROM upcoming_instructors WHERE instructor_id = ' . $id;
	
	try
	{
		$stmt = $pdo->query($sql);
		
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
		if(strtotime($row['test_date'])>= strtotime('now'))
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
		}
	};
	echo "</table>\n\n";
	echo "<br/>";
	echo "<a href='create_test.php'>Create Test</a><br />";
	echo "<br/>";
	echo "<a href='instructors_test_log.php'>Test Log</a><br />";
	echo "</form>\n";
 
}

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
	display_upcoming($pdo, $id);

}
main();

returnButton();
?>
