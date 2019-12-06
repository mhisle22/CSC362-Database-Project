<?php

function main()
{
$html = <<<'HTML'
<html>
  <head>
    <title>Welcome to the DC3 Database!</title>
  </head>
  <body>
  <p>
  Welcome to the DC3 Database!
  </p>
    <h1>Menu</h1>
    <ul>
      <li><a href="index.php">Main Menu</a></li>
      <li><a href="students.php">Club Members</a></li>
      <li><a href="ducks.php">Ducks</a></li>
    </ul>
  </body>
</html>
HTML;

echo $html;
}

//generic error handling scaffolding
//credits go to Dr. Allen for the code
function debug_message($message, $continued=FALSE)
{
	$html = '<span style"color:orange;">';
	$html .= $message . '</span>';
	if ($continued == FALSE) {
	  $html .= '<br />';
	}
	$html .= "\n";
	echo $html;
}

//code to connect to a PostgreSQL DB.
//returns a PDO object if create
//credits go to Dr. Allen for the code
function connect_to_psql($db, $verbose=FALSE)
{
  $host = 'localhost';
  $user = 'mark_hisle';
  $pass = 'password'; //i don't have one. Probably not the best idea but...

  $dsn = "pgsql:host=$host;dbname=$db;user=$user;password=$pass";
  $options = [
    PDO::ATTR_ERRMODE			=> PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE 	=> PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES		=> false,
  ];
  try {
    if ($verbose) {
      debug_message('Connecting to PostgreSQL DB...', TRUE);
    }
    $pdo = new PDO($dsn, $user, $pass, $options);
    if ($verbose) {
      debug_message('Success!');
    }
    return $pdo;
  }
  catch (\PDOException $e) {
    debug_message('Error: Could not connect to database! Aborting!');
    debug_message($dsn);
    debug_message($e);
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
  }
}

//function to create input boxes allowing for
//entry of new student
//See students.php
function makeEntry()
{
  echo '<h1> Hello </h1>';
  echo '<form>';
  echo 'First name: <input type="text" name="fname"><br>';
  echo 'Last name: <input type="text" name="lname"><br>';
  echo '<input type="submit" value="Submit">';
  echo '</form>';

}


//retrieve duck_reports
//Reference: ducks.php
function getDuckReports($pdo)
{
	$sql = 'SELECT * FROM report_ducks;';
	$output = '';
	try {
	  $output = $pdo->query($sql);
	  debug_message('Table retrieve query succeeded');

	} catch (\PDOException $e) {
	  debug_message('PSQL query failed.');
	  debug_message('Error message: ' . $e);
	}

	return $output;
}

//retrieve student_reports
//Reference: students.php and student_adder.php
function getStudentReports($pdo)
{
	$sql = 'SELECT * FROM report_students;';
	$output = '';
	try {
	  $output = $pdo->query($sql);
	  debug_message('Query succeeded');

	} catch (\PDOException $e) {
	  debug_message('PSQL query failed.');
	  debug_message('Error message: ' . $e);
	}

	return $output;
}

//retrieve student_id based on their first and last name
//Reference: students.php
function getStudID($student_first_name, $student_last_name, $pdo)
{
	$sql = 'SELECT student_id FROM students ';
	$sql .= "WHERE student_first_name = '{$student_first_name}' ";
	$sql .= "AND student_last_name = '{$student_last_name}';";

	debug_message('$sql = '  .$sql);

	$stmt = NULL;
	try {
	$stmt = $pdo->query($sql); 
	debug_message('Query successful');
	} catch (\PDOException $e) {
	debug_message('ERROR: Query failed:');
	debug_message('PSQL Error Message: ' . $e);
	}

	$stud_id = NULL;
	foreach($stmt as $row)
	{
	  $stud_id = $row['student_id'];
	}

	return $stud_id;
}

//delete a student from the DB based on their ID
//Reference: students.php
function deleteStud($stud_id, $pdo)
{
	$sql = 'DELETE FROM students ';
	$sql .= "WHERE student_id = '{$stud_id}'; ";

	debug_message('$sql = '  .$sql);

	try {
	  $pdo->query($sql); 
	  debug_message('Deletion successful');
	} catch (\PDOException $e) {
	  debug_message('ERROR: Deletion failed:');
	  debug_message('PSQL Error Message: ' . $e);
	}
}

//delete references to a student in students_ducks
//ideally ran after a student is deleted
//Reference: students.php
function deleteStudRelations($stud_id, $pdo)
{
	$sql = 'DELETE FROM students_ducks ';
	$sql .= "WHERE student_id = '{$stud_id}'; ";

	debug_message('$sql = '  .$sql);

	try {
	  $pdo->query($sql); 
	  debug_message('Deletion successful');
	} catch (\PDOException $e) {
	  debug_message('ERROR: Deletion failed:');
	  debug_message('PSQL Error Message: ' . $e);
	}
}

//Retrieve the id of a dorm based on its name
//Reference: students.php
function getDormID($dorm, $pdo)
{
	$sql = 'SELECT dorm_id FROM dorms ';
	$sql .= "WHERE dorm_name = '{$dorm}' ";

  
	$stmt = NULL;
  	try {
        $stmt = $pdo->query($sql); 
    	debug_message('Query successful');
  	} catch (\PDOException $e) {
    	debug_message('ERROR: Query failed:');
	debug_message('PSQL Error Message: ' . $e);
	}
	$dorm_id = NULL;
	foreach($stmt as $row)
	{
	  $dorm_id = $row['dorm_id'];
	}

	if(empty($dorm_id))
	{
		debug_message("\n----ERROR: This dorm does not exist.----\n");
	}
	return $dorm_id;
}

//Update all of a students information as necessary
//if data is same, it is simply set to the previous value
//Reference: students.php
function updateStudent($student_first_name, $student_last_name, $dorm_id, $stud_id, $pdo)
{
	$sql = 'UPDATE students ';
	$sql .= "SET student_last_name = '{$student_last_name}', ";
	$sql .= "student_first_name = '{$student_first_name}', ";
	$sql .= "dorm_id = '{$dorm_id}' ";
	$sql .= "WHERE student_id = '{$stud_id}'; ";

	debug_message('$sql = '  .$sql);

	try {
	  $pdo->query($sql); 
	  debug_message('Update successful');
	} catch (\PDOException $e) {
	  debug_message('ERROR: Update failed:');
	  debug_message('PSQL Error Message: ' . $e);
	}
}


//retrieve duck_id based on its name
//Reference: students.php
function getDuckID($duck, $pdo)
{
	//now get correct duck_id
	$sql = 'SELECT duck_id FROM ducks ';
	$sql .= "WHERE duck_name = '{$duck}'; ";

	debug_message('$sql = '  .$sql);

	$stmt = NULL;
	try {
	$stmt = $pdo->query($sql); 
	debug_message('Query successful');
	} catch (\PDOException $e) {
	debug_message('ERROR: Query failed:');
	debug_message('PSQL Error Message: ' . $e);
	}
	$duck_id = NULL;
	foreach($stmt as $row)
	{
	  $duck_id = $row['duck_id'];
	}

	return $duck_id;
}

//create a new relation between a student and a duck
//Reference: students.php
function makeNewRelation($stud_id, $duck_id, $pdo)
{
	$data = [
	  'student_id' => $stud_id,
	  'duck_id' => $duck_id,
	];
	    
	$sql = 'INSERT INTO students_ducks ';
	$sql .= '(student_id, duck_id)';
	$sql .= ' VALUES ';
	$sql .= '(:student_id, :duck_id);';

	debug_message('$sql = ' . $sql);

	try {
	  $pdo->prepare($sql)->execute($data);
	  debug_message('Record added successfully');
	} catch (\PDOException $e) {
	  debug_message('ERROR: Cannot add student_duck:');
	  debug_message('PSQL Error Message: ' . $e);
	}
}



?>
