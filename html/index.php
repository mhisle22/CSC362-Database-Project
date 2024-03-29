<?php

/*
 * Home/login page of the ACME DB.
 * Contains simple input boxes allowing for user verification.
 * Depending upon identity of user, this page will either
 * link to the instructor, student, or proctor main page.
 *
 * See functions.php for login code.
 *
 */

//set up error handling, can turn off once in production mode
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//phpinfo();
require_once("functions.php");

$pdo = connect_to_psql('gunsnrosesproject', $verbose=TRUE);

/*
 * Main login page
 *
 * Redirects user to their respective role's main page
 * depending upon whether they are a student, instructor,
 * or proctor.
 *
 * See new_user.php for more info.
*/


// Iif the user is already logged in, them somewhere
/*if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	
    header("location: proctor.php");
    exit;
}*/
 
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate username and password
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT user_id, username, password, role FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if username exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["user_id"];
                        $username = $row["username"];
			$hashed_password = $row["password"];
			$role = $row["role"];

                        if(password_verify($password, $hashed_password)){
                            // Password is good, let's get out of here
                            
                            // Store data in the session array
			    session_start();
			    $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
			    // Redirect user to respective screen
			    if($role === "proctor") {
				    header("location: proctor.php?username=" . 					    					$username);
			    }
			    elseif($role === "student") {
				    header("location: student.php?username=" .  				   					$username);
			    }
			    elseif($role === "instructor") {
				    header("location: instructor.php?username=" 				    					. $username);
			    }
			    else {
			       // idk how this would happen but be prepared I guess
			       debug_message("User has no role, look into this");
			    }

                        } else{
                            // Display an error message if password is not valid
			    $password_err = "Invalid username or login.";
			    // So technically the username could be correct here,
			    // but this is more secure
                        }
                    }
                } else{
                    // Username not found
                    $username_err = "No account found with that username.";
                }
            } else{
                debug_message("Error- something went wrong. Please try again later.");
            }
        }
        
    }
    
}

//lastly, print a message if we successfuly redirected from registration page
$query_string = ($_SERVER['QUERY_STRING']);
$almost = explode("=", $query_string);
if(isset($almost[1])) {
	$success = $almost[1];

	if($success)
	{
		echo "<h4 style='color:green'>Successfully registered user!</h4>";
	}
}


addCSS();

?>

<!DOCTYPE html>
<html>
<head>
<title>ACME Testing Center Database</title>
</head>
<body>
<h1>Welcome to the ACME Testing Center Database!</h1>
<h4>Please provide your login information to continue.</h4>

<h2>Login</h2>
<p>Please fill in your username and password to continue.</p>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
	<!-- This lets you do the fancy error popup -->
	<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
        	<label>Username</label>
		<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
		<!-- This also lets you do the fancy error popup -->
                <span class="help-block"><?php echo $username_err; ?></span>
	</div>    
	<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
		<br />
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
	</div>
	<br />
	<input type="submit" class="btn btn-primary" value="Login">
</form>

<br /><h3>Don't have an account?</h3>
<h4>Click here to create a new user.</h4>
<form action="new_user.php">
<input type="submit" name="create_account" value="Create an account">
</form>
