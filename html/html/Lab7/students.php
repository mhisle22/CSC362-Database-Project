<?php

//error handling code
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("functions.php");

main();
$pdo = connect_to_psql('lab07', $verbose=TRUE);

//when delete button is pressed...
if(isset($_POST['del'])) {

	debug_message('Deleting from DB');

	$stud_name = $_POST['stud_name'];
	$names = explode(" ", $stud_name);

	$student_last_name = $names[0];
	$student_first_name = $names[1];
	$dorm = $_POST['drm_name'];
	$students_ducks = $_POST['std_dcks'];


	$stud_id = getStudID($student_first_name, $student_last_name, $pdo);

	//perform two deletes with retrieved student_id
	deleteStud($stud_id, $pdo);


	//same as above, but in students_ducks
	//you can't own a duck if you no longer exist (you know what I mean)
	deleteStudRelations($stud_id, $pdo);
}

//When edit button is pressed...
if(isset($_POST['edit'])) {

	debug_message('Editing DB');

	$stud_name = $_POST['stud_name'];
	$names = explode(" ", $stud_name);

	$student_last_name = $names[0];
	$student_first_name = $names[1];
	$dorm = $_POST['drm_name'];
	$students_ducks = $_POST['std_dcks'];

	//first of all, get correct dorm query
	$dorm_id = getDormID($dorm, $pdo);


 	//now get correct student_id
	//$stud_id = getStudID($student_first_name, $student_last_name, $pdo); 
	$stud_id = $_POST['hidden_id'];

	//actually update students with this data now
	updateStudent($student_first_name, $student_last_name, $dorm_id, $stud_id, $pdo);

	//now iteratively change all duck relations if needed
	if(isset($students_ducks) && !empty($students_ducks))
	{
	//first, remove old entries of student duck pairings
	deleteStudRelations($stud_id, $pdo);
	  
	$ducks = explode(',', $students_ducks);
	foreach($ducks as $duck)
	{
       
		//now get correct duck_id
		$duck_id = getDuckID($duck, $pdo);

    		//now create new relation of students ducks
		makeNewRelation($stud_id, $duck_id, $pdo);
		

	}

  }
}

//add student code. Please functionize this in future
if(isset($_POST['add_student'])) {
  debug_message('Adding to DB');
  $student_first_name = $_POST['fname'];
  $student_last_name = $_POST['lname'];
  $dorm = $_POST['dorm'];
  $students_ducks = $_POST['ducks'];

  //retrieve dorm_id
  $dorm_id = getDormID($dorm, $pdo);

  $data = [
    'student_first_name' => $student_first_name,
    'student_last_name' => $student_last_name,
    'dorm_id' => $dorm_id,
  ];

  $sql = 'INSERT INTO students ';
  $sql .= '(student_first_name, student_last_name, dorm_id)';
  $sql .= ' VALUES ';
  $sql .= '(:student_first_name, :student_last_name, :dorm_id);';

  debug_message('$sql = ' . $sql);

  try {
    $pdo->prepare($sql)->execute($data);
    debug_message('Record added successfully');
  } catch (\PDOException $e) {
    debug_message('ERROR: Cannot add student:');
    debug_message('PSQL Error Message: ' . $e);
  }

  //now attempt to make duck relation if necessary
  if(isset($students_ducks) && !empty($students_ducks))
  {
	//retrieve student id
	$stud_id = getStudID($student_first_name, $student_last_name, $pdo);
       

    $ducks = explode(',', $students_ducks);
    foreach($ducks as $duck)
    {

	//get duck id
	$duck_id = getDuckID($duck, $pdo);

	makeNewRelation($stud_id, $duck_id, $pdo);
  

    }

  }
}

$output = getStudentReports($pdo);

echo "<h1>Club Members</h1>";

echo "\n<table style='border: 1px solid black;'>\n";
echo "<tr><th>Student Name<th>Dorm Name</th><th>Student's Ducks</th>
	<th>Delete</th><th>Edit</th></tr>\n";
foreach ($output as $row)
{
	echo "<tr><form method='post'>\n";
	echo "<input type='hidden' name='hidden_id' value='" .
	$row['student_id'] . "'>";
	echo "<td>" . "<input type='text' name='stud_name' value='" . 
	$row['student_name'] . "'></td>\n";
	echo "<td>" . "<input type='text' name='drm_name' value='" . 
	$row['dorm_name'] . "'></td>\n";
	echo "<td>" . "<input type='text' name='std_dcks' value='" . 
	$row['students_ducks'] . "'></td>\n";
	echo "<td><input type='submit' value='Delete' name='del'></td>\n";
	echo "<td><input type='submit' value='Edit' name='edit'></td>\n";
	echo "</form></tr>\n";
}
echo "</table>\n";

echo "<form action='student_adder.php'>";
echo "<input type='submit' value='Add new student'>";
echo "</form>";


?>
