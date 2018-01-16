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

if(isset($_REQUEST)) {
    
    $userID = 0;
    $orderBy = "`UPLOADED_ON` DESC";
    
    if(isset($_REQUEST["user"]))
    {
        $userID = $_REQUEST["user"];
    }
        
   if(isset($_REQUEST["order"]))
    {
        switch($_REQUEST["order"]) {
            case "recent":
                $orderBy = "`UPLOADED_ON` DESC";
                break;
            case "oldest":
                $orderBy = "`UPLOADED_ON` ASC";
                break;
            case "name_asce":
                $orderBy = "`NAME` ASC";
                break;
            case "name_desc":
                $orderBy = "`NAME` DESC";
                break;
        }
    }
    
    loadAllImagesWith($userID, $orderBy);
}



function loadAllImagesWith($userID, $orderBy) {

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
        $sql = "SELECT * FROM `image` ";
        $sql .= "WHERE `PRIVATE` = 0 ";
        if($userID != 0) {
            $sql .= "AND `IMG_USER_ID` = ".$userID." ";
        }
        $sql .= "ORDER BY ".$orderBy;
        
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
}


?>