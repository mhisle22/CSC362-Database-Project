<?php

//error handling
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//phpinfo();

require_once('functions.php');

function displayLog($pdo)
{

}


function testLogHTML($pdo)
{
	$html = <<<_HTML_
	<h1>Test Log</h1>
	<form method='post'>
	<div>
	<button type='submit' value='month'>Month</button>
	<button type='submit' value='week'>Week</button>
	<button type='submit' value='year'>Year</button>
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
	testLogHTML($pdo);
}

main();
?>
