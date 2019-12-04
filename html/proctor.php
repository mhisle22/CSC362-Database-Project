<?php


//set up error handling, can turn off once in production mode
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//phpinfo();
require_once("functions.php");

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
    echo "<input type='hidden' value='$proid' name='proid'>";
    echo "<input type='hidden' value='$testid' name='testid'>";
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
function main($pdo)
{

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

        //display info
	today_schedule($pdo);
	//work_schedule($pdo);
	}
main($pdo);
?>
