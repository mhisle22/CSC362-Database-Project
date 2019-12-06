<?php


//set up error handling, can turn off once in production mode
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//phpinfo();
require_once("functions.php");
addCSS();

$pdo = connect_to_psql('project', $verbose=TRUE);

function today_schedule($pdo)
{
  $sql='SELECT * FROM today_schedule';
  try
  {
    $stmt = $pdo->query($sql);
    debug_message('Query successful');
  }
  catch (\PDOException $e)
  {
    debug_message('DB Query Failed. Error: ' . $e);
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
  }
  //display
  echo "<h1>Today's schedule</h1>";
  echo '<form method="post">' . "\n";
  $labels = ['Student Name', 'Test ID', 'Start Time', 'End Time', '', '', ''];
  $cols =['proctor_id','student_name','test_id','test_start_time','test_end_time'];
  echo "<table>";
  echo "<tr>";
  foreach($labels as $th)
  {
    echo "<th>" . $th . "</th>";
  }
  echo "</tr>\n";
  foreach ($stmt as $row)
  {
    echo "<tr>\n";
    foreach (array_slice($cols, 1) as $col)
    {
      $td = $row[$col];
      echo "<td>" . $td . "</td>\n";
    }
    $proid=$row[$cols[0]];
    $testid=$row['test_id'];
    echo "<input type='hidden' value='$proid' name='toproid'>";
    echo "<input type='hidden' value='$testid' name='totestid'>";
    echo "<td><input type='submit' value='Arrived' name='arr'></td>\n";
    echo "<td><input type='submit' value='Ended' name='end'></td>\n";
    echo "<td><input type='submit' value='Delete' name='del'></td>\n";
    echo "</tr>\n";
    }
    echo "</table>\n\n";
    echo "</form>";
    debug_message('Returning from function.');
  }
}
if(isset($_POST['arr']))
{
  echo "student arrived!";
}
if(isset($_POST['end']))
{
  echo "student ended the exam!";
}
if(isset($_POST['del']))
{
 //$stmt=$pdo->prepare("UPDATE reservations SET proctor_id=NULL WHERE test_id=?;");
 $stmt=$pdo->prepare("DELETE FROM reservations WHERE test_id=?;");
 $stmt->execute([$_POST['totestid']]);
}
function work_schedule($pdo)
{
  $sql='SELECT * FROM work_schedule';
  try
  {
    $stmt = $pdo->query($sql);
    debug_message('Query successful');
  }
  catch (\PDOException $e)
  {
    debug_message('DB Query Failed. Error: ' . $e);
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
  }
  //display
  echo "<h1>Work schedule</h1>";
  echo '<form method="post">' . "\n";
  $labels = ['Date', 'Time Slot','', ''];
  $cols =['proctor_id','test_date','time_slot'];
  echo "<table>";
  echo "<tr>";
  foreach($labels as $th)
  {
    echo "<th>" . $th . "</th>";
  }
  echo "</tr>\n";
  foreach ($stmt as $row)
  {
    echo "<tr>\n";
    foreach (array_slice($cols, 1) as $col)
    {
      $td = $row[$col];
      echo "<td>" . $td . "</td>\n";
    }
    $proid=$row[$cols[0]];
    $testid=$row['test_id'];
    echo "<input type='hidden' value='$proid' name='upproid'>";
    echo "<input type='hidden' value='$testid' name='uptestid'>";
    echo "<td><input type='submit' value='Delete' name='delete'></td>\n";
    echo "</tr>\n";
  }
  echo "</table>\n\n";
  echo "</form>";
}
if(isset($_POST['delete']))
{
  //$stmt=$pdo->prepare("UPDATE reservations SET proctor_id=NULL WHERE test_id=?;");
  $stmt=$pdo->prepare("DELETE FROM reservations WHERE test_id=?;");
  $stmt->execute([$_POST['uptestid']]);
}
function main($pdo)
{

//<<<<<<< HEAD
function today_schedule($pdo,$id)
{
  
  $sql='SELECT * FROM today_schedule WHERE proctor_id='.$id.';';
  try
  {
    $stmt = $pdo->query($sql);
    //debug_message('Query successful');
  }
  catch(\PDOException $e)
  {
    debug_message('DB Query Failed. Error: ' . $e);
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
  }
  //display
  echo "<h3>Today's Schedule</h3>";
  $labels = ['Student Name', 'Test ID','Seat ID', 'Start Time', 'End Time', '', '', ''];
  $cols =['proctor_id','student_name','test_id','seat_id','test_start_time','test_end_time'];
  echo "<table>";
  echo "<tr>";
  foreach($labels as $th)
  {
    echo "<th>" . $th . "</th>";
  }
  echo "</tr>\n";
  foreach ($stmt as $row)
  {
    if(strtotime($row['test_date'])==strtotime(date("Y/m/d"))){
    echo "<tr>\n";
    foreach (array_slice($cols, 1) as $col)
    {
      $td = $row[$col];
      echo "<td>" . $td . "</td>\n";
    }
    $proid=$row[$cols[0]];
    $testid=$row['test_id'];
    echo '<form method="post">' . "\n";                                       
    echo "<input type='hidden' value='$testid' name='artestid'>";
    echo "<td><input type='submit' value='Arrived' name='arr' id='arrived'></td>\n";
    echo "</form>";
    echo '<form method="post">' . "\n";
    echo "<input type='hidden' value='$testid' name='entestid'>";
    echo "<td><input type='submit' value='Ended' name='end' id='ended'></td>\n";
    echo "</form>";
    echo '<form method="post">' . "\n";
    echo "<input type='hidden' value='$testid' name='intestid'>";
    echo "<td><input type='submit' value='Incomplete' name='inc'></td>\n";
    echo "</tr>\n";
  }}
    echo "</table>";
    echo "</form>";
}

if(isset($_POST['arr']))
{
  $stmt=$pdo->prepare("UPDATE tests SET test_status='Started' WHERE test_id=? AND test_status!='Completed';");
  $stmt->execute([$_POST['artestid']]);  
}
if(isset($_POST['end']))
{  
  $stmt=$pdo->prepare("UPDATE tests SET test_status='Completed' WHERE test_id=? AND test_status='Started';");
  $stmt->execute([$_POST['entestid']]);
}
if(isset($_POST['inc']))
{
  $stmt=$pdo->prepare("UPDATE tests SET test_status='Incomplete' WHERE test_id=?;");
  $stmt->execute([$_POST['intestid']]);
}

function work_schedule($pdo,$id)
{
  $sql='SELECT * FROM work_schedule';
  try
  {
    $stmt = $pdo->query($sql);
    //debug_message('Query successful');
  }
  catch (\PDOException $e)
  {
    debug_message('DB Query Failed. Error: ' . $e);
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
  }
  //display
  echo "<h3>Work Schedule</h3>";
  echo '<form method="post">' . "\n";
  $labels = ['Date', 'Time Slot',''];
  $cols =['proctor_id','test_date','time_slot'];
  echo "<table>";
  echo "<tr>";
  foreach($labels as $th)
  {
    echo "<th>" . $th . "</th>";
  }
  echo "</tr>\n";
  foreach ($stmt as $row)
  {
    if(strtotime($row['test_date']) > strtotime(date("Y-m-d"))){
    echo "<tr>\n";
    foreach (array_slice($cols, 1) as $col)
    {
      $td = $row[$col];
      echo "<td>" . $td . "</td>\n";
    }
    $proid=$row[$cols[0]];
    $testid=$row['test_id'];
    echo '<form method="post">' . "\n";
    echo "<input type='hidden' value='$proid' name='upproid'>";
    echo "<input type='hidden' value='$testid' name='uptestid'>";
    echo "<td><input type='submit' value='Delete' name='delete'></td>\n";
    echo "</form>";
    echo "</tr>\n";
  }}
  echo "</table>";
}

if(isset($_POST['delete']))
{
  $stmt=$pdo->prepare("UPDATE reservations SET proctor_id=NULL WHERE test_id=?;");
  $stmt->execute([$_POST['uptestid']]);
}

function main($pdo)
{
  //get the user info from header
  $query_string = ($_SERVER['QUERY_STRING']);
  $name=explode("=", $query_string);
  $username=explode(".", $name[1]);
  $firstname=ucfirst($username[0]);
  $lastname=ucfirst($username[1]);
  session_start();
  //$_SESSION["username"];
  $id=$_SESSION["id"];

  $cur_date=date("Y/m/d");

  //basic layout of login page
  $html = '<html>';
  $html .= '<head>';
  $html .= '<title>ACME Testing Center Database</title>';
  $html .= '</head>';
  $html .= '<body>';
  $html .= '<h1>Proctor Screen</h1>';
  $html .= '<h5>Logged in as ' . $firstname . ' '.$lastname.'</h5>';
 /* $html .= '<script>';
  $html .= 'function arrived(){ document.getElementById("arrived").disabled = true; document.getElementById("ended").disabled = false;}'
  $html .= '</script>';*/
  $html .= '</body>';
  $html .= '</html>';	
  echo $html;

  //display info
  today_schedule($pdo,$id);
  work_schedule($pdo,$id);
}
main($pdo);
//=======
        //get the user info from header
	$query_string = ($_SERVER['QUERY_STRING']);
	$name=explode("=", $query_string);
	$username=$name[1];

        //basic layout of login page
	$html = '<html>';
	$html .= '<head>';
        $html .= '<title>ACME Testing Center Database</title>';
	$html .= '</head>';
	$html .= '<h1>Proctor Screen</h1>';
	$html .= '<h2>Logged in as ' . $username . '</h2>';
	$html .= '<body>';
	$html .= '</body>';
	$html .= '</html>';
	echo $html;
//>>>>>>> a8af7201e05908aefac2634281cbc147b0ce745e

        //display info
	today_schedule($pdo);
	work_schedule($pdo);
	}
main($pdo);
?>




