<!DOCTYPE html>
<html>

<?php 
    //This needs to be called in every normal display page to check if the user is logged in
    include 'php/control/sessioncontrol.php';
    //DEBUG: REMOVE ON RELEASE
    //print_r($_SESSION);
?>

<head>
    <title>toomanyimages</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="logos/favicon.png" type="image/ico">
</head>

<body>
    <nav>
        <button type="button" class="nav_button" id="upload_nav_button" onclick="document.location.href='php/views/uploadForm.php'" />
        <button class="nav_button" id="search_nav_button" onclick="showSearchInput()"></button>
        <img src="logos/tmi_logo_text.png" alt="toomanyimages logo" height="36" width="101" id="logo_nav_img" onclick="document.location.href='index.php'">
        <button class="nav_button" id="login_nav_button" onclick="onLoginFormClick()"></button>
        <div class="login_nav_content">
            <?php getlinksindropdown();?>
        </div>
    </nav>
    <section id="main">
        <div id="imagesearch" style="display:none;"><input id="imgsearchinp" type="text" oninput="reloadImages()"></div>
        <div class="minibar">
            Sortieren nach:
            <select id="orderBy" onchange="reloadImages()">
                <option value="recent">Neueste zuerst</option>
                <option value="oldest">Älteste zuerst</option>
                <option value="name_asce">Name (aufsteigend)</option>
                <option value="name_desc">Name (absteigend)</option>
            </select>
        </div>
        <div id="imagecontainer"></div>
    </section>
    <footer>
        <a href="php/views/impressum.php" class="footer_link">Impressum</a>
    </footer>
</body>

<script type="text/javascript">
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

        var request = "php/control/loadImages.php?";

        request += "user=0";

        var orderBy = document.getElementById("orderBy");
        var orderValue = orderBy.options[orderBy.selectedIndex].value;

        request += "&order=" + orderValue;
        
        var searchinput = document.getElementById("imgsearchinp").value;
        
        request += "&search=" + searchinput;

        xmlhttp.open("GET", request, true);
        xmlhttp.send();
    }


    function showSearchInput() {
        var x = document.getElementById("imagesearch");
            if (x.style.display == "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
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
            document.location.href = 'php/views/loginform.php'
        } else {
            document.location.href = 'php/views/user.php?id=' + sessionValue;
        }
    }

</script>

<?php 

function getlinksindropdown() {
    
    if(isset($_SESSION["session_user_ID"]) && !empty($_SESSION["session_user_ID"])) {
        echo '<a href="php/views/user.php?id='.$_SESSION["session_user_ID"].'">Profil</a>';
        echo '<a href="php/control/logout.php">Ausloggen</a>';
    } else {
     echo '<a href="php/views/loginForm.php">Einloggen</a><a href="php/views/registrationForm.php">Registrieren</a>';
    }
}

    
    ?>

</html>
