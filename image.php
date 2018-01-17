<!DOCTYPE html>
<html>

<?php
    //This needs to be called in every normal display page to check if the user is logged i
    include 'sessioncontrol.php';

    
    if(!isset($_GET["id"]) || !checkIfImageExists($_GET["id"])) {
        http_response_code(404);
        include('404.php');
        die();   
    } else if (!checkIfImageMayBeViewed($_GET["id"])) {
        include('404.php');
        echo "Sorry, you don't have permission to view this image.";
        die();
    }
?>
    
<head>
    <title><?php getImageName(); ?> - toomanyimages</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav>
            <button type="button" class="nav_button" id="upload_nav_button" onclick="document.location.href='uploadForm.php'" />
        <button class="nav_button" id="search_nav_button"></button>
        <img src="logos/imgup.png" alt="imgup logo" height="36" width="128" id="logo_nav_img" onclick="document.location.href='index.php'">
        <button class="nav_button" id="login_nav_button" onclick="onLoginFormClick()"></button>
    </nav>
    <section id="main">
        <div id="image_detail_container">
            <div id="image_detail_view"><img src="<?php loadImage(); ?>"></div>
            <div id="image_detail_name">Name: <?php getImageName(); ?></div>
            <div id="image_detail_description">Beschreibung: <?php getImageDescription(); ?></div>
            <div id="user_for_image">Hochgeladen von: <?php getUserForImage(); ?></div>
            <div id="image_uploaded_on">Hochgeladen am: <?php getImageUploadedOn(); ?></div>
        </div>
    </section>
    <footer>    
        <a href="impressum.html" class="footer_link">Impressum</a>
    </footer>
</body>

<?php
    
function loadImage() {
    //DATABASE ACCESS VARIABLES - shouldn't be modified
    $servername = "localhost";
    $db_username = "php_projekt";
    $db_password = "php_projekt";
    $dbname = "awd_projekt";

     $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    //Check if the connection succeeded, otherwise abort
    if ($conn->connect_error) {
        //An error occured, return FALSE for error handling
        $errorMessage = "We're sorry, there was an error trying to establish a connection to our database. Please try again later.";
        echo "ERROR";
        return;
    }

    //Get row where username or email exists
    $sql = "SELECT `PATH` FROM `image` WHERE `ID` = '".$_GET["id"]."'";

    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()) {
        echo $row["PATH"];
    }
}
    
function getImageName() {
    //DATABASE ACCESS VARIABLES - shouldn't be modified
    $servername = "localhost";
    $db_username = "php_projekt";
    $db_password = "php_projekt";
    $dbname = "awd_projekt";
    
     $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    //Check if the connection succeeded, otherwise abort
    if ($conn->connect_error) {
        //An error occured, return FALSE for error handling
        $errorMessage = "We're sorry, there was an error trying to establish a connection to our database. Please try again later.";
        echo "ERROR";
        return;
    }
    
    //Get row where username or email exists
    $sql = "SELECT `NAME` FROM `image` WHERE `ID` = '".$_GET["id"]."'";
    
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        echo $row["NAME"];
    }
}
    
function getImageDescription() {
    //DATABASE ACCESS VARIABLES - shouldn't be modified
    $servername = "localhost";
    $db_username = "php_projekt";
    $db_password = "php_projekt";
    $dbname = "awd_projekt";

     $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    //Check if the connection succeeded, otherwise abort
    if ($conn->connect_error) {
        //An error occured, return FALSE for error handling
        $errorMessage = "We're sorry, there was an error trying to establish a connection to our database. Please try again later.";
        echo "ERROR";
        return;
    }

    //Get row where username or email exists
    $sql = "SELECT `DESCRIPTION` FROM `image` WHERE `ID` = '".$_GET["id"]."'";

    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()) {
        echo $row["DESCRIPTION"];
    }
}

function checkIfImageExists($id) {
      //DATABASE ACCESS VARIABLES - shouldn't be modified
    $servername = "localhost";
    $db_username = "php_projekt";
    $db_password = "php_projekt";
    $dbname = "awd_projekt";
    
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    //Check if the connection succeeded, otherwise abort
    if ($conn->connect_error) {
        //An error occured, return FALSE for error handling
        $errorMessage = "We're sorry, there was an error trying to establish a connection to our database. Please try again later.";
        echo "ERROR";
        return;
    }
    
    //Get row where username or email exists
    $sql = "SELECT COUNT(`ID`) AS IMG_EXISTS FROM `image` WHERE `ID` = ".$id;
    
    $result = $conn->query($sql);
    
    $row = $result->fetch_assoc();
    
    if($row["IMG_EXISTS"] == 1) {
        return true;
    } else {
        return false;
    }
}

function checkIfImageMayBeViewed($id) {

    $servername = "localhost";
    $db_username = "php_projekt";
    $db_password = "php_projekt";
    $dbname = "awd_projekt";
    
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    //Check if the connection succeeded, otherwise abort
    if ($conn->connect_error) {
        //An error occured, return FALSE for error handling
        $errorMessage = "We're sorry, there was an error trying to establish a connection to our database. Please try again later.";
        echo "ERROR";
        return;
    }
    
    //Get row where username or email exists
    $sql = "SELECT `PRIVATE`FROM `image` WHERE `ID` = ".$id;
    
    $result = $conn->query($sql);
    
    $row = $result->fetch_assoc();
    
    if($row["PRIVATE"] == 0) {
        return true;
    } else if($id == $_SESSION["session_user_ID"]){
        
        return true;
    } else {
        return false;
    }
    
}

function getUserForImage() {

    //DATABASE ACCESS VARIABLES - shouldn't be modified
    $servername = "localhost";
    $db_username = "php_projekt";
    $db_password = "php_projekt";
    $dbname = "awd_projekt";
    
     $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    //Check if the connection succeeded, otherwise abort
    if ($conn->connect_error) {
        //An error occured, return FALSE for error handling
        $errorMessage = "We're sorry, there was an error trying to establish a connection to our database. Please try again later.";
        echo "ERROR";
        return;
    }
    
    //Get row where username or email exists
    $sql = "SELECT `user`.`USERNAME` FROM `user` INNER JOIN `image` ON `image`.`IMG_USER_ID` = `user`.`ID` WHERE `image`.`ID` = '".$_GET["id"]."'";
    
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        echo $row["USERNAME"];
    }
}
                   
function getImageUploadedOn() {

    //DATABASE ACCESS VARIABLES - shouldn't be modified
    $servername = "localhost";
    $db_username = "php_projekt";
    $db_password = "php_projekt";
    $dbname = "awd_projekt";
    
     $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    //Check if the connection succeeded, otherwise abort
    if ($conn->connect_error) {
        //An error occured, return FALSE for error handling
        $errorMessage = "We're sorry, there was an error trying to establish a connection to our database. Please try again later.";
        echo "ERROR";
        return;
    }
    
    //Get row where username or email exists
    $sql = "SELECT `UPLOADED_ON` FROM `image` WHERE `ID` = '".$_GET["id"]."'";
    
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        echo $row["UPLOADED_ON"];
    }
}            
            
?>
    
</html>