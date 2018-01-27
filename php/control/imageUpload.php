<?php

/** IMAGE UPLOAD SCRIPT - created by Tom Quinders
*
* This script will handle the upload of an image. It is called when the user
* submits the filled out form in uploadForm.php and returns an error message
* should it fail at any point.
*
* The main function of this script is uploadImage() which will be called when
* this script is confirmed to be called via POST SUBMIT.
*
* 4 TODOS LEFT
*/

//GLOBAL VARIABLE - Error message may be set at multiple times
$errorMessage = "";

//If this script was called via POST, begin the upload via uploadImage()
if(isset($_POST["submit"])) {
    
    if(uploadImage()) {
        //Return empty handed to signify the upload was completed
        //TODO: Maybe place a success message to return a value either way?
        //TODO: Return to the actual site without header
        /*return;*/
        header("Location:../../index.php");
    } else {
        //Return the error message to be displayed in the original form
        //TODO: Return the error instead of echoing it
        /*return $errorMessage;*/
        echo $errorMessage;
    }
}

/** UPLOAD IMAGE - created by Tom Quinders
*
* This is the main function of this script. The function will take the data
* submitted by the POST method, both the file itself and the data the user added.
*
* First the function will check if the image is valid and generate a name for it,
* next it will try to parse the data input by the user and add it to the database via
* addImageToDB().
*
* Returns TRUE if the files is uploaded correctly, FALSE if it runs into problems
* at any point. Should it run into a problem, the global variable $errorMessage
* will be set to an error message that should be displayed. 
*/
function uploadImage() {
    
    //FILEPATH VARIABLES - shouldn't be modified
    $target_dir = "uploaded_images/";
    $fileName = basename($_FILES["imageToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $sizeLimit = 5000000; //5 MB

    //CONTROL VARIABLES - may be modified
    $private = 0;
    $userID = 0;
    global $errorMessage;
    
    //Check if uploaded file is actually an image
    $check = getimagesize($_FILES["imageToUpload"]["tmp_name"]);
    if($check == false) {
        /*echo "File is not an image -  ".$check["mime"].".";*/
        $errorMessage = "Your file is not an image.";
        return FALSE;
    }
    
    //Check file size against limit
    if ($_FILES["imageToUpload"]["size"] > $sizeLimit) {
        $errorMessage = "Your image's filesize is too large. You may upload images up to 5MB.";
        return FALSE;
    }
    
    //Check for valid file type
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $errorMessage = "Only JPG, JPEG, PNG & GIF files are allowed.";
        return FALSE;
    }
    
    //Generate target filename
    $target_file = $target_dir.generateRandomString(mt_rand(5,10)).".".$imageFileType;
    //Check if filename exists, if so, generate a new one
    while(file_exists("../../".$target_file)) {
        $target_file = $target_dir.generateRandomString(mt_rand(5,10)).".".$imageFileType;
    }
    
    //Get the user from the session, terminate if no user logged in
    session_start();
    if(isset($_SESSION["session_user_ID"]) && !empty($_SESSION["session_user_ID"])) {
            $userID = $_SESSION["session_user_ID"];
    } else {
        $errorMessage = "You aren't logged in! Please log in or register to upload an image.";
        return FALSE;
    }

    //Move the uploaded file from the temp location to its target destination
    if (move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], "../../".$target_file)) {
        /*echo "The file ". basename( $_FILES["imageToUpload"]["name"]). " has been uploaded.";*/
        //Check the checkbox for whether the user wants the image to be private or public
        if(isset($_POST['img_private']) && $_POST['img_private'] == 'private') {
            $private = 1;
        }

        //Add image data to database via addImageToDB()
        if(addImageToDB($_POST['img_name'], $_POST['img_desc'], $target_file, $userID, $private)) {
            //Everything worked in the file upload process, return to original page
            return TRUE;
        } else {
            //Delete file if database connection fails to prevent errors and dead files
            unlink("../../".$target_file);
            //There was an error in the database add process, output an error message
            return FALSE;
        }
    } else {
        //There was an error in the file move process, output an error message
        $errorMessage = "We're sorry, something went wrong uploading your image. Please try again later.";
        return FALSE;
    }
    
}

/** ADD IMAGE TO DATABASE - created by Tom Quinders
*
* This function adds an image's data to the database. The data will be taken from
* uploadForm.php via POST and handed over in the parameters.
*
* Returns TRUE if the operation succeeded, FALSE if the operation fails at any point.
* Should the operation run into a problem, the global variable $errorMessage will be
* set to a message to be displayed to the user.
*/
function addImageToDB($name, $desc, $path, $userid, $private) {
    
    //DATABASE ACCESS VARIABLES - shouldn't be modified
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
    
    //Create SQL statement from parameters given from the uploadForm.php via POST
    $sql = "INSERT INTO image (NAME, DESCRIPTION, PATH, IMG_USER_ID, PRIVATE) VALUES ('".$name."', '".$desc."', '".$path."', ".$userid.", ".$private.")";

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
    return $success;
}
 
/** GENERATE RANDOM STRING - added by Tom Quinders
*
* This function generates a random String for the image name. This function was taken
* from a Stackoverflow suggestion [1] and slightly modified for better performance.
*
* This function is called when the target filename is set in the uploadImage function.
* The created filename will be checked and if it should exist already, which is highly
* unlikely, it will be generated anew.
*
* [1] https://stackoverflow.com/questions/4356289/php-random-string-generator
*     Online; last access on 11.01.2018
*/
function generateRandomString($length = 10) {
    
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>
