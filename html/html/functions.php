
<?php


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
      //debug_message('Connecting to PostgreSQL DB...', TRUE);
    }
    $pdo = new PDO($dsn, $user, $pass, $options);
    if ($verbose) {
      //debug_message('Success!');
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

function returnButton()
{
  echo '<br><br><br><br>';
  echo '<a href="index.php">Return to login page</a>';
}

function addCSS()
{
$css = <<<_CSS_
<style type="text/css">

body {
	background: #0B0C10;
	font-family: sans-serif;
	color: #C5C6C7;
}
a, p, label {
	color: #C5C6C7;
	font-family: sans-serif;
	font-size: 1.4vw;
}
a:hover{
	color:#f6b93b;
}
input {	
	font-family: sans-serif;
	font-size: 1vw;
}
td, th {
    border: 1px solid #dddddd;
        text-align: left;
	
	    padding: 6px;
	      }
	      tr:nth-child(even) {
	 background-color: #dddddd;
		      }
		      
table {
	background: #C5C6C7;
	border-radius:4px;
	border: 2px solid #5D5C61;
	color: black;
	font-size: 1.28vw;
	border-collapse: collapse;
}
input[type=text],input[type=password],input[type=submit],input[type=reset]{
	border-radius: 4px;
}
input[type=submit]:hover,input[type=reset]:hover,button:hover{
color: #ffffff;
background: #f6b93b;
border-radius: 4px;
border-color: #f6b93b;
text-shadow: 0px 0px 6px rgba(255, 255, 255, 1);
-webkit-box-shadow: 0px 5px 40px -10px rgba(0,0,0,0.57);
-moz-box-shadow: 0px 5px 40px -10px rgba(0,0,0,0.57);
transition: all 0.4s ease 0s;
}
h1, h2, h3, h4, h5, h6 {
	color: #66FCF1;	
	font-family: sans-serif;
}

h1 {
  font-size: 4vw;
}
h2 {
  font-size: 2.8vw;
}
h3 {
  font-size: 2.5vw;
}
h4 {
  font-size: 1.8vw;
}
h5 {
  font-size: 1.5vw;
}

</style>
_CSS_;

echo $css;

}


?>
