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
 * Code from same soucr as register new user code
 * This is not my original code, although several
 * modifications have been made to it for 
 * compaitiblity with this project.
 *
 * See new_user.php for link.
*/


// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: proctor.php");
    exit;
}
 
 
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
    
    // Validate credentials
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
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
			    // Redirect user to respective screen
			    if($role === "proctor") {
				    header("location: proctor.php");
			    }
			    elseif($role === "student") {
				    header("location: student.php");
			    }
			    elseif($role === "instructor") {
				    header("location: instructor.php");
			    }
			    else {
			       debug_message("User has no role, look into this");
			    }

                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}

?>

<!DOCTYPE html>
<html>
<head>
<title>ACME Testing Center Database</title>
</head>
<body>
<h1>Welcome to the ACME Testing Center Database!</h1>
<h4>Please provide your login informtation to continue.</h4>

<div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
	    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
	    <br />
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
	    </div>
	    <br />
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>

<br /><br /><h3>Don't have an account?</h3>
<h4>Click here to create a new user.</h4>
<form action="new_user.php">
<input type="submit" name="create_account" value="Create an account">
</form>
