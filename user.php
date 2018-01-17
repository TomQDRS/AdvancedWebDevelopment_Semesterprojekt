<!DOCTYPE html>
<html>

<?php 
    if(!isset($_GET["id"]) || !checkIfUserExists($_GET["id"])) {
        http_response_code(404);
        include('404.php');
        die();   
    } /*else {
        if(!checkIfUserExists($_GET["id"])) {
            http_response_code(404);
        include('404.php');
        die(); 
        }
    }*/
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
        <img src="logos/imgup.png" alt="imgup logo" height="36" width="128" id="logo_nav_img" onclick="document.location.href='index.php'">
        <button class="nav_button" id="login_nav_button" onclick="onLoginFormClick()"></button>
    </nav>
    <section id="main">
        <div id="user_info_container">
            <div id="user_name">Username: <?php getUserName(); ?></div>
            <div id="user_registered_on">Registriert am: <?php getUserRegisteredOn(); ?></div>
        </div>
        <div class="minibar"><div>Bilder dieses Nutzers:</div>  Sortieren nach:
            <select id="orderBy" onchange="reloadImages()">
                <option value="recent">Neueste zuerst</option>
                <option value="oldest">Älteste zuerst</option>
                <option value="name_asce">Name (aufsteigend)</option>
                <option value="name_desc">Name (absteigend)</option>
            </select></div>
        <div id="imagecontainer"></div>
    </section>
    <footer>    
        <a href="impressum.html" class="footer_link">Impressum</a>
    </footer>
</body>

<?php

function checkIfUserExists($id) {
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
    $sql = "SELECT COUNT(`ID`) AS USR_EXISTS FROM `user` WHERE `ID` = ".$id;
    
    $result = $conn->query($sql);
    
    $row = $result->fetch_assoc();
    
    if($row["USR_EXISTS"] == 1) {
        return true;
    } else {
        return false;
    }
    
    
}

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
    $sql = "SELECT `USERNAME` FROM `user` WHERE `ID` = '".$_GET["id"]."'";
    
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
    $sql = "SELECT `REGISTERED_ON` FROM `user` WHERE `ID` = '".$_GET["id"]."'";
    
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        echo $row["REGISTERED_ON"];
    }
}            
            
?>
    
<script type="text/javascript" language="javascript">
    
     window.onload = reloadImages();
    
    function reloadImages() {

        var myNode = document.getElementById("imagecontainer");
        while (myNode.firstChild) {
            myNode.removeChild(myNode.firstChild);
        }
                
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            document.getElementById("imagecontainer").innerHTML = this.responseText;
        }
        
        var request = "loadImages.php?";
        
        var userID = <?php echo $_GET["id"]?>;
        request += "user=" + userID;
        
        var private = "public";
        if(userID == <?php echo $_SESSION["session_user_ID"]?>) {
           private = "both";
        }

        request += "&private=" + private;

        var orderBy = document.getElementById("orderBy");
        var orderValue = orderBy.options[orderBy.selectedIndex].value; 
        
        request += "&order=" + orderValue;
                
        xmlhttp.open("GET", request, true);
        xmlhttp.send();
    }

    
</script>
    
</html>