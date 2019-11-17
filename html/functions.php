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




?>
