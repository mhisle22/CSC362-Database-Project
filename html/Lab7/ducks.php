<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("functions.php");

main();
$pdo = connect_to_psql('lab07', $verbose=TRUE);

/*
$sql = 'SELECT * FROM report_ducks;';
$output = '';
try {
  $output = $pdo->query($sql);
  debug_message('Table retrieve query succeeded');

} catch (\PDOException $e) {
  debug_message('PSQL query failed.');
  debug_message('Error message: ' . $e);
}*/

$output = getDuckReports($pdo);

echo "<h1>Club Ducks</h1>";

echo "\n<table style='border: 1px solid black;'>\n";
echo "<tr><th>Duck Name</th><th>Duck's Students</th></tr>\n";
foreach ($output as $row)
{
	echo "<tr>\n";
	echo "<td>" . $row['duck_name'] . "</td>\n";
	echo "<td>" . $row['ducks_students'] . "</td>\n";
	echo "</tr>\n";
}
echo "</table>\n";

?>
