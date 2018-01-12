<?php 

/** LOAD IMAGES SCRIPT - created by Tom Quinders
*
* This script handles loading the images that should be
* displayed to the page it is called in via "require", in this
* case "index.php".
*
* WORK IN PROGRESS
* TODO: Accept parameters in some way so that the load image
*       query can be filtered for only the wanted images
*/

//DATABASE ACCESS VARIABLES - shouldn't be modified
$servername = "localhost";
$username = "php_projekt";
$password = "php_projekt";
$dbname = "awd_projekt";

//IMAGE DATA ARRAYS - will be filled with data from sql query
$imagePaths = [];
$imageNames = [];

//Begin connection to database
$conn = new mysqli($servername, $username, $password, $dbname);

//Check if the connection succeeded, otherwise abort
if ($conn->connect_error) {
    //An error occured
    echo "We're sorry, there was an error trying to establish a connection to our database. Please try again later."; 
} else {

    //SELECT all public images, ordered by upload date
    //TODO: Change SQL statement based on applied filters
    $sql = "SELECT * FROM `image` WHERE `PRIVATE` = 0 ORDER BY `UPLOADED_ON` DESC";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

            $imagePaths[] = $row["PATH"];
            $imageNames[] = $row["NAME"];

        }
    } else {
        echo "No images found. Consider uploading one yourself!";
    }

    //Load each image from "imagePaths" into an imagefield container.
    //TODO: Consider handling this more elegantly like in a dictionary with
    //key value pairs. Right now this is very error-potential heavy.
    $counter = 0;

    foreach ($imagePaths as $image) {

        echo '<div class = "imagefield">';
        echo '<img class="uploaded_image" src="'.$image.'"/>';
        echo '<div>'.$imageNames[$counter].'</div>';
        echo '</div>';

        $counter++;
    }
}

?>