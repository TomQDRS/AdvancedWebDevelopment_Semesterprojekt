<?php

/** CHECK FOR REMEMBER ME SCRIPT - created by Tom Quinders
*
* This script is called from multiple pages on the website via INCLUDE to check whether a 
* session exists or not and, if it doesn't, whether a cookie is set on the client to remember
* a user.
*
* Ideas on how this script executes are largely following a suggestion from an answer to
* a stackoverflow question on how to implement a "remember me" function [2].
*
* After starting the session on the page and checking whether or not one with an
* associated userID already exists. Should it exist it will just return, should it not, there
* will be a search for a cookie. 
*
* If the cookie exists, the token with the userID in the cookie will be checked against the
* ones saved in the database. Should this fail the user won't notice and the function returns, 
* should it succeed a session will be created with the userID set to the remembered user. 
*
* [2] https://stackoverflow.com/questions/1354999/keep-me-logged-in-the-best-approach/17266448#17266448
* Online; last access on 15.01.2018
*/

//Starting the session on the page
session_start();
//Check if user exists in the session and end the script if that's the case
if(isset($_SESSION["session_user_ID"]) && !empty($_SESSION["session_user_ID"])) {
    return;
} else {
    //No session found at the moment, call rememberMe() to check for remembered usertoken
    rememberMe();
}

/** REMEMBER ME - created by Tom Quinders
* 
* This function is the heart of the script that checks whether a cookie exists from the
* "remember me" feature of the login form. If the cookie data matches the one saved for
* the user in the database, a session is created for the user as if he logged in already.
*/
function rememberMe() {
    /*print_r("rememberMe called");*/
    //Check if cookie is set and save string for "rememberme" in $cookie if it does.
    $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
    if ($cookie) {
        //Get user, token and mac from the string via exploding it at :
        list ($user, $token, $mac) = explode(':', $cookie);
        
        /*print_r($user);
        print_r(":");
        print_r($token);
        print_r(":");
        print_r($mac);*/
        
        //Check if the hash in the cookie follows the set format rules
        if (!hash_equals(hash_hmac('sha256', $user . ':' . $token, 'DrFbVqit9x68349Uz8yji9LNHHN9q8FgyV1eokpRvfQk4GgOVjqMa1GpD7aA1VBG5djqtlcxZ1aP1h3oVzp6N3Mctt8X3fHQchh3fwJ655403fnp3J3NGc6N4H3jFxSJ'), $mac)) {
            return;
        }
        
        //Get usertoken from database
        $usertoken = fetchTokenByUserID($user);
        if($usertoken != null) {
            //If token exists, check if the hashes match for both tokens
            if (hash_equals($usertoken, $token)) {
                //Create session from ID in token
                $_SESSION["session_user_ID"] = $user;
            }
        }     
    }
}

/** FETCH TOKEN BY USER ID - created by Tom Quinders
*
* Get the login token for the user from the database. Returns the token as NULL if it's empty,
* which is checked in rememberMe().
*/
function fetchTokenByUserID($user) {
    
    //DATABASE ACCES VARIABLES - should not be changed
    $servername = "localhost";
    $username = "php_projekt";
    $password = "php_projekt";
    $dbname = "awd_projekt";
    
    //Begin connection to database
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    //Create SQL statement from parameters given from the uploadForm.php via POST
    $sql = "SELECT `LOGINTOKEN` FROM `user` WHERE ID = '".$user."'";

     $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            //Get login token from database
            return $row["LOGINTOKEN"];
        }
    }
}

?>