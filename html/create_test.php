<?php

//error handling
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//phpinfo();
require_once('functions.php');

function displayForm($pdo, $inst_id_empty_err, $stud_id_empty_err, $date_empty_err, $start_empty_err, $length_empty_err, $version_empty_err, $course_empty_err)
{
	
	$html = <<<_HTML_
	<h1>Create New Test</h1>
	<form method='post'>
	<table>
	<div class="form-group <?php echo (!empty($inst_id_empty_err)) ? 'has-error' : ''; ?>">
		<tr>
			<td>Instructur ID</td>
			<td><input type='text' name='instructor_id' placeholder='ex:000000' class='form-control' /></td>
			<td><span class='help-block'><?php echo $inst_id_empty_err; ?></span></td>
			
		</tr>
	</div>
	<div class="form-group <?php echo (!empty($stud_id_empty_err)) ? 'has-error' : ''; ?>">
		<tr>
			<td>Student ID</td>
			<td><input type='text' name='student_id' placeholder='ex: 000000' class='form-control'  />
			<span class='help-block'><?php echo $stud_id_empty_err; ?></span></td>
		</tr>
	</div>
	<div class="form-group <?php echo (!empty($date_empty_err)) ? 'has-error' : ''; ?>">
		<tr>
			<td>Test Date</td>
			<td><input type='text' name='test_date' placeholder='ex: mm/dd/yyyy' class='form-control' />
			<span class='help-block'><?php echo $date_empty_err; ?></span></td>
		</tr>
	</div>
	<div class="form-group <?php echo (!empty($start_empty_err)) ? 'has-error' : ''; ?>">
		<tr>
			<td>Test Start Time</td>
			<td><input type='text' name='test_start_time' placeholder='ex: 10:00' class='form-control' />
			<span class='help-block'><?php echo $start_empty_err; ?></span>	</td>
		</tr>
	</div>
	<div class="form-group <?php echo (!empty($length_empty_err)) ? 'has-error' : ''; ?>">
		<tr>
			<td>Test Length</td>
			<td><input type='text' name='test_length' placeholder='ex: 120 (minutes)' class='form-control' />
			<span class='help-block'><?php echo $length_empty_err; ?></span></td>
		</tr>
	</div>
	<div class="form-group <?php echo (!empty($version_empty_err)) ? 'has-error' : ''; ?>">
		<tr>
			<td>Test Version</td>
			<td><input type='text' name='test_version' placeholder='ex: A' class='form-control' />
			<span class='help-block'><?php echo $version_empty_err; ?></span></td>
		</tr>
	</div>
	<div class="form-group <?php echo (!empty($course_empty_err)) ? 'has-error' : ''; ?>">
		<tr>
			<td>Course</td>
			<td><input type='text' name='test_course' placeholder='ex:CSC220a' class='form-control' />
			<span class='help-block'><?php echo $course_empty_err; ?></span></td>
		</tr>
	</div>
	<tr>
		<td>File</td><td><input type='file' name='test_blob' /></td>
	</tr>
	<tr>

		<td><button type='submitTest' name='submitbutton' value='submitTest'>Create Test</button></td>
	
	</tr>
	</table>
	</form>
_HTML_;
echo $html;
}
function sendBack()
{
	$html = <<<_HTML_
		<h2>Test Successfully Created</h2>
		<p><a href="instructor.php">Click Here</a> to return to the main page</p>
		<p><a href="create_test.php">Click Here</a> to create another Test</p>
_HTML_;
	echo $html;
}
function main()
{
	$pdo = connect_to_psql('gunsnrosesproject', $verbose=TRUE);
	$inst_id_empty_err='';
	$stud_id_empty_err='';
	$date_empty_err='';
	$start_empty_err='';
	$length_empty_err='';
	$version_empty_err='';
	$course_empty_err='';	
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
	
		
		//test start time must be a TIME and NOT NULL. 
		//test length must be an INTERVAL and NOT LONGER THAN OPEN HOURS (open to lunch or lunch to dinner
		//test version must be NOT NULL
		//course must be NOT NULL and 6 CHARS
		
		$sql = 'INSERT INTO tests (instructor_id, test_date, test_length, test_course, test_version, test_start_time, test_status) ';
		$sql .= 'VALUES (:inst_id, :test_date, :test_length, :test_course, :test_version, :test_start_time, :test_status)';
			
		$sql2 = 'INSERT INTO tests (instructor_id, test_date, test_length, test_course, test_version, test_start_time, test_file_blob, test_status) ';
		$sql2 .= 'VALUES (:inst_id, :test_date, :test_length, :test_course, :test_version, :test_start_time, :test_file_blob, :test_status)';

		//write if statement to ensure none are null
		if((!empty($_POST['instructor_id'])) && (!empty($_POST['student_id'])) && 
			(!empty($_POST['test_date'])) && (!empty($_POST['test_start_time'])) && 
			(!empty($_POST['test_length'])) && (!empty($_POST['test_version'])) && 
			(!empty($_POST['test_course'])))
		{
			
			//test date=date
			//test start time=time
			//test length = interval 

			//test length must be less than 4 hours
			//test start time must be within time
			if (is_numeric($_POST['instructor_id'])==false)
			{
				$inst_id_empty_err = 'instructor id must be a number';
			}
			elseif (is_numeric($_POST['student_id'])==false)
			{
				$stud_id_empty_err = 'student id must be a number';
			}
			//doesn't work
			elseif (DateTime::createFromFormat('mm/dd/yyyy', $_POST['test_date']) !== false)
			{
				$date_empty_err = 'Please enter a valid date';
			}
			elseif (TimeSpan.TryParse($_POST['test_start_time'])===false)
			{
				$start_empty_err = 'Please enter a valid time';
			}
			else
			{
				$good = 'Good';	
				try
				{
					if(empty($_POST['test_file_blob']))
					{
						$stmt = $pdo->prepare($sql);
						$data = [ 'inst_id'	=> $_POST['instructor_id'],
							'test_date'	=> $_POST['test_date'],
							'test_length'	=> $_POST['test_length'],
							'test_version'	=> $_POST['test_version'],
							'test_course'	=> $_POST['test_course'],
							'test_start_time'	=> $_POST['test_start_time'],
				       			'test_status'	=> $good];
					
						$stmt->execute($data);
						
					}
					else
					{
						$stmt = $pdo->prepare($sql2);
	
						$data = ['inst_id'	=> $_POST['instructor_id'],
							'test_date'	=> $_POST['test_date'],
							'test_length'	=> $_POST['test_length'],
							'test_course'	=> $_POST['test_course'],
							'test_version'	=> $_POST['test_version'],
							'test_start_time'	=> $_POST['test_start_time'], 
							'test_file_blob'	=> $_POST['test_file_blob'],
							'test_status'	=> $good];
						$stmt->execute($data);
			 
					}
					sendBack();
			 		
				}
				catch (\PDOException $e)
				{
					if ($e->getCode() == 23505)
					{
						throw new \PDOException($e->getMessage(), (int)$e->getCode());
					}
					else
					{
						throw new \PDOException($e->getMessage(), (int)$e->getCode());
					}
				}
			}
		}
		else
		{
				echo 'Please fill out all fields (excluding file)';
		}
		
		
	}
	 
	displayForm($pdo, $inst_id_empty_err, $stud_id_empty_err, $date_empty_err, $start_empty_err, $length_empty_err, $version_empty_err, $course_empty_err);
}

main();
?>

