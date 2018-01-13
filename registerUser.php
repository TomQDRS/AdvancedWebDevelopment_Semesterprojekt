<?php

/** REGISTER USER SCRIPT - created by Tom Quinders
* 
* This script tries to register the user by adding the data input by the
* user in registrationForm.php sent via POST to the database table
* "user".  It is called when the user submits the filled out form and returns an 
* error message should it fail at any point.
*
* The main function of this script is registerUser() which will be called when
* this script is confirmed to be called via POST SUBMIT.
*
* 4 TODOS LEFT
*/

//GLOBAL VARIABLE - Error message may be set at multiple times
$errorMessage = "";

//If this script was called via POST, begin the registration via registerUser().
if(isset($_POST["submit"])) {
    
    if(registerUser()) {
        //Return empty handed to signify the upload was completed
        //TODO: Maybe place a success message to return a value either way?
        //TODO: Return to the actual site without header
        //TODO: CREATE SESSION
        /*return;*/
        header("Location:index.php");
    } else {
        //Return the error message to be displayed in the original form
        //TODO: Return the error instead of echoing it
        /*return $errorMessage;*/
        echo $errorMessage;
    }
}

/** REGISTER USER - created by Tom Quinders
*
* This function takes the data the script received via POST and registers a user
* to the database. First the function checks whether or not either the username
* or email adress already exist in the database. Should neither already exist in the
* database, the new user will be registered with USERNAME, EMAIL and a hashed PASSWORD.
*
* Return will be TRUE if the operation is successfull and FALSE should it fail at any
* point. The global variable errorMessage will be modified should FALSE be returned,
* so a useful message can be displayed to the user.
*/
function registerUser() {
 
    global $errorMessage;
    
    //DATABASE ACCESS VARIABLES - shouldn't be modified
    $servername = "localhost";
    $db_username = "php_projekt";
    $db_password = "php_projekt";
    $dbname = "awd_projekt";
    
    //Getting input data from form via POST
    $username = $_POST["usr_name"];
    $email = $_POST["usr_mail"];
    //Getting the password from POST and immediately hashing it
    $hashedpass = password_hash($_POST["usr_password"], PASSWORD_BCRYPT, ['cost' => 10]);
    
    //Start database connection
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    //Check if the connection succeeded, otherwise abort
    if ($conn->connect_error) {
        /*die("Connection failed: " . $conn->connect_error);*/
        //An error occured, return FALSE for error handling
        $errorMessage = "We're sorry, there was an error trying to establish a connection to our database. Please try again later.";
        return FALSE;
    } 
    
    //Check if username already exists
    $sql = "SELECT * FROM `user` WHERE `USERNAME` = '".$username."'";
    if(mysqli_num_rows($conn->query($sql)) != 0) {
        //Results aren't empty, username exists, return FALSE for error handling
        $errorMessage = "This username is not available. Please consider using a different username.";
        return FALSE;
    } 
    
    //Check if email already exists
    $sql = "SELECT * FROM `user` WHERE `EMAIL` = '".$email."'";
    if(mysqli_num_rows($conn->query($sql)) != 0) {
        //Results aren't empty, email exists, return FALSE for error handling
        $errorMessage = "This E-Mail adress is not available. Please consider using a different E-Mail.";
        return FALSE;
    } 
    
    //Everything has gone right so far, try adding the user to the database
    $sql = "INSERT INTO `user`(`USERNAME`,`EMAIL`,`PASSWORD`) VALUES('".$username."','".$email."','".$hashedpass."')";

     if ($conn->query($sql) === TRUE) {
        //The operation was a success, return TRUE
        return TRUE;
    } else {
        //An error occured, return FALSE for error handling
        $errorMessage = "We're sorry, there was an error trying to save your input data to our database. Please try again later.";
        return FALSE;
    }
}

?>
