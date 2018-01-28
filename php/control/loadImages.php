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
    $orderBy = "`image`.`UPLOADED_ON` DESC";
    $private = "`image`.`PRIVATE` = 0";
    $path = "";
    $search = "";
    
    if(isset($_GET["user"]))
    {
        $userID = $_GET["user"];
    }
        
   if(isset($_GET["order"]))
    {
        switch($_GET["order"]) {
            case "recent":
                $orderBy = "`image`.`UPLOADED_ON` DESC";
                break;
            case "oldest":
                $orderBy = "`image`.`UPLOADED_ON` ASC";
                break;
            case "name_asce":
                $orderBy = "`image`.`NAME` ASC";
                break;
            case "name_desc":
                $orderBy = "`image`.`NAME` DESC";
                break;
        }
    }
    
    if(isset($_GET["private"])) {
        switch($_GET["private"]) {
            case "public":
                $private = "`image`.`PRIVATE` = 0";
                break;
            case "private":
                $private = "`image`.`PRIVATE` = 1";
                break;
            case "both":
                $private = "(`image`.`PRIVATE` = 0 OR `image`.`PRIVATE` = 1)";
                break;
        }
    }
    
    if(isset($_GET["search"])) {
        $search = $_GET["search"];
    }
    
    if(isset($_GET["path"]) && ($_GET["path"] == 2)) {
        $path .= "../../";
    }
    
    loadAllImagesWith($userID, $orderBy, $private, $path, $search);
}



function loadAllImagesWith($userID, $orderBy, $private, $path, $search) {

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
        $sql = "SELECT DISTINCT `image`.`PATH`, `image`.`NAME`, `image`.`ID` FROM `image` LEFT JOIN `image_tag` ON `image`.`ID` = `image_tag`.`IMAGE_ID` LEFT JOIN `tag` ON `image_tag`.`TAG_ID` = `tag`.`ID` ";
        $sql .= "WHERE ".$private." ";
        if($userID != 0) {
            $sql .= "AND `image`.`IMG_USER_ID` = ".$userID." ";
        }
        if($search != "") {
            $sql .= " AND (`image`.`NAME` LIKE '%".$search."%' OR `image`.`DESCRIPTION` LIKE '%".$search."%' OR `tag`.`NAME` LIKE '%".$search."%') ";
        }
        $sql .= "ORDER BY ".$orderBy;
        
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                echo '<div class = "imagefield">';

                 if(isset($_GET["path"]) && ($_GET["path"] == 2)) {
                     echo '<img class="uploaded_image" "document.location.href=\'image.php?id='.$row["ID"].'\'" src="'.$path.$row["PATH"].'"/>';
                } else { 
                    echo '<img class="uploaded_image" onclick = "document.location.href=\'php/views/image.php?id='.$row["ID"].'\'" src="'.$path.$row["PATH"].'"/>';
                 }
                
                echo '<div class="imagename" style="text-align: center;">'.$row["NAME"].'</div>';
                echo '</div>';

            }
        } else {
            echo "Sorry, no images were found!";
        }
    }
}


?>
