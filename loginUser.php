<?php

/** LOGIN USER SCRIPT - created by Tom Quinders
* 
* This script tries to login the user by validating the login data received from
* loginForm.php. It will create a session if the login is successfull.
*
* The main function of this script is loginUser() which will be called when
* this script is confirmed to be called via POST SUBMIT.
*
* 4 TODOS LEFT
*/

//GLOBAL VARIABLE - Error message may be set at multiple times
$errorMessage = "";

//If this script was called via POST, begin the registration via registerUser().
if(isset($_POST["submit"])) {
    
    if(loginUser()) {
        //Return empty handed to signify the upload was completed
        //TODO: Maybe place a success message to return a value either way?
        //TODO: Return to the actual site without header
        /*return;*/
        header("Location:index.php");
    } else {
        //Return the error message to be displayed in the original form
        //TODO: Return the error instead of echoing it
        /*return $errorMessage;*/
        echo $errorMessage;
    }
}

/** LOGIN USER - created by Tom Quinders
*
* This function takes the data the script received via POST and compares it to users
* in the database. The function tries to get the password field from a row where either
* the email or the username matches the login input.
*
* Return will be TRUE if the operation is successfull and FALSE should it fail at any
* point. The global variable errorMessage will be modified should FALSE be returned,
* so a useful message can be displayed to the user.
*/
function loginUser() {
 
    global $errorMessage;
    
    //DATABASE ACCESS VARIABLES - shouldn't be modified
    $servername = "localhost";
    $db_username = "php_projekt";
    $db_password = "php_projekt";
    $dbname = "awd_projekt";
    
    //Getting login data from form via POST
    $login = $_POST["usr_login"];
    $password = $_POST["usr_password"];
    
    //Start database connection
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    //Check if the connection succeeded, otherwise abort
    if ($conn->connect_error) {
        //An error occured, return FALSE for error handling
        $errorMessage = "We're sorry, there was an error trying to establish a connection to our database. Please try again later.";
        return FALSE;
    } 
    
    //Get row where username or email exists
    $sql = "SELECT `PASSWORD` FROM `user` WHERE `USERNAME` = '".$login."' OR `EMAIL` = '".$login."'";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        while($row = $result->fetch_assoc()) {
            //Get Password from database
            $hashedPasswordFromDB = $row["PASSWORD"];
        }
        
        //Compare input password to hashed password via password_verify()
        if(password_verify($password, $hashedPasswordFromDB)) {
            //TODO: CREATE SESSION
            return TRUE;
        } else {
            $errorMessage = 'Invalid password. Please try again.';
            return FALSE;
        }
    } else {
        //Neither USERNAME nor EMAIL contains the login
        $errorMessage = 'Invalid username or email. Please try again.';
        return FALSE;
     }
}

?>