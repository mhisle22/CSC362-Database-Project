<?php

/*
 * 	Guns 'n Roses DB Project
 *
 * 	Code to add a new user to the user table
 */

// include DB access code
require_once "functions.php";
addCSS();

//set up error handling, can turn off once in production mode
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pdo = connect_to_psql('gunsnrosesproject', $verbose=TRUE);

// Define vars
$username = $password = $confirm_password = $id = "";
// These will be used to check errors as is done in PHP land
$username_err = $password_err = $confirm_password_err = $id_err = "";

// Process data when submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Code to check if username exists or not
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
	    
	$sql = "SELECT user_id FROM users WHERE username = :username";
        // shorthand for did this query work or not
        if($stmt = $pdo->prepare($sql)){
	    // this binds variables as parameters to query, thus making it more secure
	    // at least that's what the internet told me
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
	    debug_message('$sql= ' . $sql);
            // run statement
	    if($stmt->execute()){
		// if the username was taken already, there will (should) be only one row
                if($stmt->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                debug_message("Error! Something went wrong. Please try again later.");
            }
        }
         
        // need to close statement
        unset($stmt);
    }
    
    // check password in same kind of way as above
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
	//password must be > 6 characters cuz security. And everyone does that anyways
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // check confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
	$confirm_password = trim($_POST["confirm_password"]);
	//if the two are not equal, print error message that they aren't equal
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // check user id in once again the same way
    if(empty(trim($_POST["id"]))){
        $id_err = "Please enter an id.";     
    } elseif(strlen(trim($_POST["id"])) > 6){
	// ID's must be Centre style, this can be changed in future
        $id_err = "ID must be between 0 and 6 characters.";
    } else{
        $id = trim($_POST["id"]);
    } 

    //lastly, get data for new user role from radio buttons
    $role = NULL;
    if(isset($_POST["role"]))
    {
	$role = $_POST["role"];
    }
    else
    {
	debug_message("Error with setting role");
    }


    // Check if there are errors one last time before moving on
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($id_err)){
        
        // insert statement
        $sql = "INSERT INTO users (user_id, username, password, role) VALUES (:user_id, :username, :password, :role)";
        // once again, shorthand for continue on if this worked
        if($stmt = $pdo->prepare($sql)){
            // bind variables as parameters
	    $stmt->bindParam(":user_id", $param_id, PDO::PARAM_STR);
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
	    $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
	    $stmt->bindParam(":role", $param_role, PDO::PARAM_STR);

	    //debug_message($password . "!");

            // Set parameters
            $param_username = $username;
	    
	    //!!!!!!! NOTE: This is where salting takes place !!!!!!!
	    $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
	    
	    $param_role = $role;
	    $param_id = $id;
            
            // attempt to execute the prepared statement
            if($stmt->execute()){
		// go back to login page, indicating success
		$success = True;
                header("location: index.php?success=" . $success);
            } else{
                echo "Something went wrong. Please try again later.";
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
    <title>Sign Up</title>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
	    </div>
            <div class="form-group <?php echo (!empty($id_err)) ? 'has-error' : ''; ?>">
                <label>ID</label>
                <input type="text" name="id" class="form-control" value="<?php echo $id; ?>">
                <span class="help-block"><?php echo $id_err; ?></span>
            </div>
	    <br />
	    <!-- My code: radio buttons to set a role for new user-->
	    <div>
		Student: <input type="radio" name="role" value="student" checked>
		<br />
		Instructor: <input type="radio" name="role" value="instructor">
		<br />
		Proctor:<input type="radio" name="role" value="proctor">
	    </div>
	    <br />
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="index.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>
