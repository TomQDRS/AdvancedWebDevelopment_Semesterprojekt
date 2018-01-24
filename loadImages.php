<?php 

/** LOAD IMAGES SCRIPT - created by Tom Quinders
*
* This script handles loading the images that should be
* displayed to the page it is called in via "require", in this
* case "index.php".
*/

//DATABASE ACCESS VARIABLES - shouldn't be modified

if(isset($_GET)) {
    
    $userID = 0;
    $orderBy = "`UPLOADED_ON` DESC";
    $private = "`PRIVATE` = 0";
    
    if(isset($_GET["user"]))
    {
        $userID = $_GET["user"];
    }
        
   if(isset($_GET["order"]))
    {
        switch($_GET["order"]) {
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
    
    if(isset($_GET["private"])) {
        switch($_GET["private"]) {
            case "public":
                $private = "`PRIVATE` = 0";
                break;
            case "private":
                $private = "`PRIVATE` = 1";
                break;
            case "both":
                $private = "(`PRIVATE` = 0 OR `PRIVATE` = 1)";
                break;
        }
    }
    
    loadAllImagesWith($userID, $orderBy, $private);
}



function loadAllImagesWith($userID, $orderBy, $private) {

    $servername = "localhost";
    $username = "php_projekt";
    $password = "php_projekt";
    $dbname = "awd_projekt";

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
        $sql .= "WHERE ".$private." ";
        if($userID != 0) {
            $sql .= "AND `IMG_USER_ID` = ".$userID." ";
        }
        $sql .= "ORDER BY ".$orderBy;
        
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {

                echo '<div class = "imagefield" onclick = "document.location.href=\'image.php?id='.$row["ID"].'\'">';
                echo '<img class="uploaded_image" src="'.$row["PATH"].'"/>';
                echo '<div style="    text-align: center;">'.$row["NAME"].'</div>';
                echo '</div>';

            }
        } else {
            echo "Sorry, no images were found!";
        }
    }
}


?>
