<?php

//error handling
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once("functions.php");
addCSS();

function main()
{
	$html= <<<_HTML_
		<h2>Test Successfully Created</h2>
		<p><a href='instructor.php'>Click Here</a> to return to the main page</p>
		<p><a href='create_test.php'>Click Here</a> to create another Test</p>
_HTML_;
echo $html;

}

main();
?>
