<!DOCTYPE html>
<html>

<?php 
    //This needs to be called in every normal display page to check if the user is logged in
    include 'checkForRememberMe.php';
    //DEBUG: REMOVE ON RELEASE
    print_r($_SESSION);
?>
    
<head>
    <title>toomanyimages</title>
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
        <a href="impressum.html" class="footer_link">Impressum</a>
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
        
        var request = "loadImages.php?";
        
        request += "user=0";
        
        var orderBy = document.getElementById("orderBy");
        var orderValue = orderBy.options[orderBy.selectedIndex].value; 
        
        request += "&order=" + orderValue;
                
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
        if(sessionValue == 0) {
            document.location.href='loginform.php'
        } else {
            document.location.href='user.php?id='.concat(sessionValue);
        }
    }

</script>
    
</html>