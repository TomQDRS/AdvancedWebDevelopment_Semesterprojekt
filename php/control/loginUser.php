<?php

/** LOGIN USER SCRIPT - created by Tom Quinders
* 
* This script tries to login the user by validating the login data received from
* loginForm.php. It will create a session if the login is successfull.
*
* The main function of this script is loginUser() which will be called when
* this script is confirmed to be called via POST SUBMIT.
*
* 2 TODOS LEFT
*/

//GLOBAL VARIABLE - Error message may be set at multiple times
$errorMessage = "";

//If this script was called via POST, begin the registration via registerUser().
if(isset($_POST["submit"])) {
    
    if(loginUser()) {
        //Return with session active
        //TODO: Maybe place a success message to return a value either way?
        header("Location:../../index.php");
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
    $sql = "SELECT `PASSWORD`, `ID` FROM `user` WHERE `USERNAME` = '".$login."' OR `EMAIL` = '".$login."'";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {

        while($row = $result->fetch_assoc()) {
            //Get Password from database
            $hashedPasswordFromDB = $row["PASSWORD"];
            $userID = $row["ID"];
        }
        
        //Compare input password to hashed password via password_verify()
        if(password_verify($password, $hashedPasswordFromDB)) {
            //call onLogin if passwords match
            onLogin($userID, $conn);
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

/** ON LOGIN - created by Tom Quinders
*
* This function is called when the login is a success. The function always starts a session
* with which the website will work from then on. Is the remember me checkbox checked, another
* part is called in which attempts are made to set a cookie that remembers a certain special
* token for the user. 
* 
* First the token is generated via generateRandomToken(), then an attempt is made to save
* it in the database for the user in the "LOGINTOKEN" field. Should that attempt succeed,
* a cookie is generated which remembers the userID with the token, hashed. The cookie expires
* in 30 days. 
*
* The ideas for this process are largely inspired by an instruction set from a stackoverflow
* answer to a question asking for the best procedure on a "remember me" function [2].
*
* [2] https://stackoverflow.com/questions/1354999/keep-me-logged-in-the-best-approach/17266448#17266448
* Online; last access on 15.01.2018
*/
function onLogin($user, $conn) {
    
    //Begin session and set current user ID to the logged in user
    session_start();
    $_SESSION["session_user_ID"] = $user;
    
    //Check the checkbox for whether the user wants to stay logged in
    if(isset($_POST['usr_rememberme']) && $_POST['usr_rememberme'] == 'rememberme') {
        print_r("rememberme found");
        //Generate token via generateRandomToken()
        $token = generateRandomToken();
        //Store the token in the DB
        if(storeTokenForUser($user, $token, $conn)) {
            //Create cookie associated with the user
            $cookie = $user . ':' . $token;
            $mac = hash_hmac('sha256', $cookie, 'DrFbVqit9x68349Uz8yji9LNHHN9q8FgyV1eokpRvfQk4GgOVjqMa1GpD7aA1VBG5djqtlcxZ1aP1h3oVzp6N3Mctt8X3fHQchh3fwJ655403fnp3J3NGc6N4H3jFxSJ');
            $cookie .= ':' . $mac;
            setcookie('rememberme', $cookie, time()+60*60*24*30);
            /*print_r("cookieset");*/
        }
    } else {
        //Remove token from the database 
        removeTokenFromUser($user, $conn);
    }
}

/** GENERATE RANDOM TOKEN - created by Tom Quinders
*
* This function generates and returns a hex token from random_bytes(256).
*/
function generateRandomToken() {
    return bin2hex(random_bytes(256));
}

/** REMOVE TOKEN FROM USER - created by Tom Quinders
*
* This function clears the "LOGINTOKEN" field from the users entry in the database, should the
* user wish to not be remembered past the active session. The field is cleared if the user
* manually logs out, but since the cookie set in onLogin($user, $conn) might expire, the field
* will be cleared for security reasons should the user login without wanting to be remembered.
*/
function removeTokenFromUser($user, $conn) {
    $sql = "UPDATE `user` SET `LOGINTOKEN` =  null WHERE `ID` = '".$user."'";
    $conn->query($sql);
}


/** STORE TOKEN FOR USER - created by Tom Quinders
*
* This function saves the generated login token in the database when the user logs in
* with the "remember me" box ticked. The function will return TRUE or FALSE depending
* on the success of the SQL query.
*/
function storeTokenForUser($user, $token, $conn) {
    $sql = "UPDATE `user` SET `LOGINTOKEN` =  '".$token."' WHERE `ID` = '".$user."'";
    if ($conn->query($sql) === TRUE) {
        //The operation was a success, return TRUE
        return TRUE;
    } else {
        //An error occured, return FALSE
        return FALSE;
    }
}

?>
