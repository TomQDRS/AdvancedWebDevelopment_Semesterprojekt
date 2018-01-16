<!DOCTYPE html>
<html>

<?php 
    //This needs to be called in every normal display page to check if the user is logged in
    include 'checkForRememberMe.php';
    //DEBUG: REMOVE ON RELEASE
    //print_r($_SESSION); 
?>
    
<head>
    <title><?php getUserName(); ?> - toomanyimages</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav>
            <button type="button" class="nav_button" id="upload_nav_button" onclick="document.location.href='uploadForm.php'" />
        <button class="nav_button" id="search_nav_button"></button>
        <img src="logos/imgup.png" alt="imgup logo" height="36" width="128" id="logo_nav_img">
        <button class="nav_button" id="login_nav_button" onclick="onLoginFormClick()"></button>
    </nav>
    <section id="main">
        <div id="user_info_container">
            <div id="user_name">Username: <?php getUserName(); ?></div>
            <div id="user_registered_on">Registriert am: <?php getUserRegisteredOn(); ?></div>
        </div>
        <div id="minibar">Bilder dieses Nutzers:</div>
        <div id="imagecontainer">
            <?php require('loadImages.php');
            loadAllImagesWith($_SESSION["session_user_ID"]);
            ?>
        </div>
    </section>
    <footer>    
        <a href="impressum.html" class="footer_link">Impressum</a>
    </footer>
</body>

<?php
            
function getUserName() {

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
    $sql = "SELECT `USERNAME` FROM `user` WHERE `ID` = '".$_SESSION["session_user_ID"]."'";
    
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        echo $row["USERNAME"];
    }
}
                   
function getUserRegisteredOn() {

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
    $sql = "SELECT `REGISTERED_ON` FROM `user` WHERE `ID` = '".$_SESSION["session_user_ID"]."'";
    
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        echo $row["REGISTERED_ON"];
    }
}            
            
?>
    

    
</html>