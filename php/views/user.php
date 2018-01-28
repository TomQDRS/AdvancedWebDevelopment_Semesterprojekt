<!DOCTYPE html>
<html>

<?php 
    
    include '../control/sessioncontrol.php';
    
    if(!isset($_GET["id"]) || !checkIfUserExists($_GET["id"])) {
        http_response_code(404);
        include('../error/404.php');
        die();   
    }
    //This needs to be called in every normal display page to check if the user is logged in
    //DEBUG: REMOVE ON RELEASE
    //print_r($_SESSION); 
?>
    
<head>
    <title><?php getUserName(); ?> - toomanyimages</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
    

<body>
       <nav>
        <button type="button" class="nav_button" id="upload_nav_button" onclick="document.location.href='uploadForm.php'"></button>
        <img src="../../logos/tmi_logo_text.png" alt="toomanyimages logo" height="36" width="101" id="logo_nav_img" onclick="document.location.href='../../index.php'">
        <button class="nav_button" id="login_nav_button" onclick="onLoginFormClick()"></button>
        <div class="login_nav_content">
            <?php getlinksindropdown();?>
        </div>
    </nav>
    <section id="main">
        <div class="user_info_container">
            <img id="user_profilepic" src="../../profile_images/standard/standard-150.png">
            <div id="user_name"><?php getUserName(); ?> </div>
			<br><br>
            <div id="user_registered_on">Registriert am: <?php getUserRegisteredOn(); ?></div>
        </div>
        <div class="minibar">Sortieren nach:
            <select id="orderBy" onchange="reloadImages()">
                <option value="recent">Neueste zuerst</option>
                <option value="oldest">Älteste zuerst</option>
                <option value="name_asce">Name (aufsteigend)</option>
                <option value="name_desc">Name (absteigend)</option>
            </select></div>
        <div id="imagecontainer"></div>
    </section>
    <footer>    
        <a href="impressum.php" class="footer_link">Impressum</a>
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
    
    
function getlinksindropdown() {
    
    if(isset($_SESSION["session_user_ID"]) && !empty($_SESSION["session_user_ID"])) {
        echo '<a href="user.php?id='.$_SESSION["session_user_ID"].'">Profil</a>';
        echo '<a href="../control/logout.php">Ausloggen</a>';
    } else {


     echo '<a href="loginForm.php">Einloggen</a><a href="registrationForm.php">Registrieren</a>';
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
        
        var request = "../control/loadImages.php?";
        
        var userID = <?php echo $_GET["id"]?>;
        request += "user=" + userID;
        
        var sessionID =   <?php if(isset($_SESSION["session_user_ID"]) && !empty($_SESSION["session_user_ID"])) {
        echo $_SESSION["session_user_ID"];
    } else {
     echo '0';
    }?>;
        
        var private = "public";
        if(userID == sessionID) {
           private = "both";
        }

        request += "&private=" + private;

        var orderBy = document.getElementById("orderBy");
        var orderValue = orderBy.options[orderBy.selectedIndex].value; 
        
        request += "&order=" + orderValue;
                
        request += "&path=" + "2";
        
        xmlhttp.open("GET", request, true);
        xmlhttp.send();
    }

    
       function onLoginFormClick() {

        var sessionValue = <?php      
            if(isset($_SESSION["session_user_ID"]) && $_SESSION["session_user_ID"] != null) {
                echo $_SESSION["session_user_ID"];
            } else {
                echo 0;
            } ?>;
        //alert(sessionValue);
        if (sessionValue == 0) {
            document.location.href = 'loginform.php'
        } else {
            document.location.href = 'user.php?id=' + sessionValue;
        }
    }
    
</script>
    
</html>