<?php

//error handling
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//phpinfo();
require_once('functions.php');

function displayForm($pdo, $empty_err)
{
	
	$html = <<<_HTML_
	<h1>Create New Test</h1>
	<form method='post'>
	<table>
	<div class="form-group <?php echo (!empty($empty_err)) ? 'has-error' : ''; ?>">
		<tr>
			<td>Instructur ID</td>
			<td><input type='text' name='instructor_id' value='ex: 000000' class='form-control' /></td>
		</tr>
		<tr>
			<td>Student ID</td>
			<td><input type='text' name='student_id' value='ex: 000000' class='form-control'  /></td>
		</tr>
		<tr>
			<td>Test Date</td>
			<td><input type='text' name='test_date' value='ex: mm/dd/yyyy' class='form-control' /></td>
		</tr>
		<tr>
			<td>Test Start Time</td>
			<td><input type='text' name='test_start_time' value='ex: 10:00' class='form-control' /></td>
		</tr>
		<tr>
			<td>Test Length</td>
			<td><input type='text' name='test_length' value='ex: 120 (minutes)' class='form-control' /></td>
		</tr>
		<tr>
			<td>Test Version</td>
			<td><input type='text' name='test_version' value='ex: A' class='form-control' /></td>
		</tr>
		<tr>
			<td>Course</td>
			<td><input type='text' name='test_course' value='ex:CSC220a' class='form-control' /></td>
		</tr>
	</div>
	<tr>
		<td>File</td><td><input type='file' name='test_blob' /></td>
	</tr>
	<tr>
		<td><button type='submitTest' value='submitTest'>Create Test</button></td>	
	</tr>
	<tr>	
		<span class='help-block'><?php echo $empty_err; ?> </span>
	</tr>
	</table>
	</form>
_HTML_;
	echo $html;
}

function main()
{
	$pdo = connect_to_psql('gunsnrosesproject', $verbose=TRUE);
	$empty_err = '';
	/*
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		//check to see if any fields (except the file input, which is optional) is empty
		if(empty(trim($_POST['instructor_id'])) || 
		empty(trim($_POST['student_id'])) ||
		empty(trim($_POST['test_date'])) ||
		empty(trim($_POST['test_start_time'])) ||
		empty(trim($_POST['test_length'])) ||
		empty(trim($_POST['test_version'])) ||
		empty(trim($_POST['test_course'])))
		{
			$empty_err = 'Please fill out all required fields.';

		}

		if(empty($empty_err))
		{
			$sql = 'INSERT INTO tests (instructor_id, test_date, test_length, test_course, test_version, test_start_time) ';
			$sql .= 'VALUES (:inst_id, :test_date, :test_length, :test_course, :test_version, :test_start_time)';
			
			$sql2 = 'INSERT INTO tests (instructor_id, test_date, test_length, test_course, test_version, test_start_time, test_file_blob) ';
			$sql2 .= 'VALUES (:inst_id, :test_date, :test_length, :test_course, :test_version, :test_start_time, test_file_blob)';

			try
			{
				if(empty($_POST['test_file_blob']))
				{
					$stmt = $pdo->prepare($sql);

					$data = [ inst_id	=> $_POST['instructor_id'],
						test_date	=> $_POST['test_date'],
						test_length	=> $_POST['test_length'],
						test_course	=> $_POST['test_course'],
						test_version	=> $_POST['test_version'],
						test_start_time	=> $_POST['test_start_time'] ];
					$stmt->execute($data);
				}
				else
				{
					$stmt = $pdo->prepare($sql2);

					$data = [inst_id	=> $_POST['instructor_id'],
						test_date	=> $_POST['test_date'],
						test_length	=> $_POST['test_length'],
						test_course	=> $_POST['test_course'],
						test_version	=> $_POST['test_version'],
						test_start_time	=> $_POST['test_start_time'] 
						test_file_blob	=> $_POST['test_file_blob'] ];
					$stmt->execute($data);
				}
					
			}
			catch (\PDOException $e)
			{
				if ($e->getCode() == 23505)
				{
					throw new \PDOException($e->getMessage(), (int)$e->getCode());
				}
			}
		}
		
		
	}
	 */
	displayForm($pdo, $empty_err);
}

main();
?>

