<?php

if(isset($_POST) & !empty($_POST)) {
    
    $servername = "localhost";
    $username = "php_projekt";
    $password = "php_projekt";
    $dbname = "awd_projekt";

    //CONTROL VARIABLES - may be modified
    $success = TRUE;
    global $errorMessage;

    //Begin connection to database
    $conn = new mysqli($servername, $username, $password, $dbname);
    //Check if the connection succeeded, otherwise abort
    if ($conn->connect_error) {
        /*die("Connection failed: " . $conn->connect_error);*/
        //An error occured, return FALSE for error handling
        $errorMessage = "We're sorry, there was an error trying to establish a connection to our database. Please try again later.";
        $success = FALSE;
        return $success;
    }
    
    mysqli_set_charset("utf8");
    $comment = mysqli_real_escape_string($conn,$_POST['comment']);
    $userID = $_POST['user'];
    $imageID = $_POST['image'];

    //Create SQL statement from parameters given from the uploadForm.php via POST
    $sql = "INSERT INTO `comment`(`CONTENT`, `CMT_USER_ID`, `CMT_IMAGE_ID`) VALUES ('".$comment."', ".$userID.", ".$imageID.")";

    //Try to query SQL statement via the open connection
    if ($conn->query($sql) === TRUE) {
    //The operation was a success, return TRUE
    $success = TRUE;
    /*echo "New record created successfully";*/
    } else {
        //An error occured, return FALSE for error handling
        $errorMessage = "We're sorry, there was an error trying to save your input data to our database. Please try again later.";
        $success = FALSE;
        /*echo "Error: " . $sql . "<br>" . $conn->error;*/
    }
    //Close connection
    $conn->close();
       // header("Location:../../index.php");
    
}

?>
