<?php

//error handling
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//phpinfo();

require_once('functions.php');
addCSS();

function displayLog($pdo, $tempArray)
{
	try
	{
		$stmt = [];
		$final = [];
		foreach($tempArray as $day)
		{
			$sql = "SELECT * FROM upcoming_instructors WHERE test_date='" . $day . "'";
			$temp = $pdo->query($sql)->fetch();
			array_push($stmt, $temp);
		}

	//	var_dump($stmt);
	//	var_dump($temp);
	//	$final= $stmt->fetch();
	//	var_dump($final);	
	}
	catch(\PDOException $e)
	{
		throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}
	 

	echo '<form method="post">' . "\n";
	//headers and columns
	$labels = ['Name', 'Date', 'Start Time', 'Test Status'];
	$cols = ['student_name', 'test_date', 'test_start_time', 'test_status'];

	echo "\n<table>\n";
	//create the headers in a row
	echo '<tr>';
	foreach($labels as $th)
	{
		echo '<th>' . $th . '</th>';
	}
	echo "</tr>\n";

	//add data to the table
	foreach ($stmt as $row)
	{
		echo "<tr>\n";
		$id = $row[$cols[0]];
		echo "<td>" . $id . "</td>\n";
		foreach (array_slice($cols, 1) as $col)
		{
			$td = $row[$col];
			echo '<td>' . $td . "</td>\n";
		}
		echo "</tr>\n";
	}
	echo "</table>\n\n";
	echo "</br>";

}


function testLogHTML($pdo)
{
	$html = <<<_HTML_
	<a href='instructor.php'>Home</a>
	<h1>Test Log</h1>
	<form method='post'>
	<div>
	<select name='year'>
		<option value=''>Year</option>
		<option value='2019'>2019</option>
		<option value='2018'>2018</option>
	</select>
	<select name='month'>
		<option value=''>Month</option>
		<option value='01'>January</option>
		<option value='02'>February</option>
		<option value='03'>March</option>
		<option value='04'>April</option>
		<option value='05'>May</option>
		<option value='06'>June</option>
		<option value='07'>July</option>
		<option value='08'>August</option>
		<option value='09'>September</option>
		<option value='10'>October</option>
		<option value='11'>November</option>
		<option value='12'>December</option>
	</select>
	<select name='week'>
		<option value=''>Week</option>
		<option value='1'>week 1</option>
		<option value='2'>week 2</option>
		<option value='3'>week 3</option>
		<option value='4'>week 4</option>
	</select>
	<button type='submitTest' value='submitFilter' name='submitFilter'>Search</button>
	</div>
	</form>


_HTML_;
	echo $html;
}

function main()
{
	$pdo = connect_to_psql('gunsnrosesproject', $verbose=TRUE);
/*
	//get the user info from header
	$query_string = ($_SERVER['QUERY_STRING']);
	$almost = explode("=", $query_string);
	var_dump($almost);
	$username = $almost[1];
 */
	//here determine what needs to be added to the where statement of the select query
	//
	testLogHTML($pdo);
	$temp = '';
	$tempArray = [];
	if (isset($_POST['submitFilter']))
	{
		if (($_POST['year'] === '') || ($_POST['month'] === '') || ($_POST['week'] === ''))
		{
			echo 'please assign all boxes to a value';
		}
		else
		{	
			$temp = $_POST['year'] . "-" . $_POST['month'] . "-";
			if ($_POST['week']==='1')
			{
				for($x = 1; $x <= 7; $x++)
				{
					$a = $temp . '0' . $x; 
					array_push($tempArray, $a);
				}
			}
			elseif($_POST['week']==='2')
			{	
				$a = $temp . '08';
				$b = $temp . '09';
				array_push($tempArray, $a, $b);
				for($x = 10; $x<=14; $x++)
				{
					$c = $temp . $x;
					array_push($tempArray, $c);
				}
			}
			elseif($_POST['week']==='3')
			{
				for ($x = 15; $x<=21; $x++)
				{
					$a = $temp . $x;
					array_push($tempArray, $a);
				}
			}
			else
			{
				for ($x=22; $x<=31; $x++)
				{
					$a = $temp . $x;
					array_push($tempArray, $a);
				}
			}

		}
		displayLog($pdo, $tempArray);
		
	}

//	testLogHTML($pdo);
	
}

main();
?>
