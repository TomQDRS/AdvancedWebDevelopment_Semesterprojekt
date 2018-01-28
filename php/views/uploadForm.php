<!DOCTYPE html>
<html>

<?php
include '../control/sessioncontrol.php';
?>
    
<head>
    <title>toomanyimages</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>
    <nav>
        <img src="../../logos/tmi_logo_text.png" alt="toomanyimages logo" height="36" width="101" id="logo_nav_img" onclick="document.location.href='../../index.php'">
        
        <button class="nav_button" id="login_nav_button" onclick="onLoginFormClick()"></button>
        <div class="login_nav_content">
            <?php getlinksindropdown();?>
        </div>
    </nav>

    <section class="formcontainer">

        <form id="imageUploadForm" action="../control/imageUpload.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">

            <input class type="file" id="imageToUpload" name="imageToUpload" style="display: none;" />

            <button type="button" class="formsubmit" id="upload_image_button" onclick="document.getElementById('imageToUpload').click();">Datei Auswählen</button>
            <input class="formtextinput" name="img_name" type="text" placeholder="Titel" required>Titel
            <input class="formtextinput" name="img_desc" type="text" placeholder="Beschreibung">Beschreibung
            <div class="formcheckboxcontainer"><input class="formcheckbox" name="img_private" type="checkbox" value="private">Bild privat hochladen</div>
            <!--Tag
            <input id="img_tag_input"  type="text" placeholder="Tag suchen oder erstellen..."><br><br>
            <div id="tagcontainer">
            
            </div>
            <br><br>-->

            <input class="formsubmit" type="submit" name="submit" value="Hochladen">
            <div id="errorcontainer" style="display:none;"></div>
        </form>
    </section>
</body>


<script type="text/javascript">
    
    var notLoggedIn = <?php if(!isset($_SESSION["session_user_ID"]) || empty($_SESSION["session_user_ID"])) {
        echo "true;";
     } else {
        echo "false;";
}
?>
        
    if(notLoggedIn) {
        showNotLoggedIn();
    }
    
    function validateForm() {

        var inp = document.getElementById('imageToUpload');
        if (inp.files.length !== 0) {
            if (inp.files[0].name.match(/\.(jpg|jpeg|png|gif)$/)) {
                if (inp.files[0].size > 5242880) {
                    displayError('Diese Datei ist größer als 5MB!');
                    return false;
                } else {
                    return true;
                }
            } else {
                displayError('Dies ist keine Bilddatei!');
                return false;
            }
        } else {
            displayError('Bitte wähle eine Datei zum Hochladen!');
            return false;
        }
    }


    function displayError(errormessage) {

        errorcont = document.getElementById('errorcontainer');
        errorcont.innerHTML = errormessage;
        errorcont.style.color = "red";
        errorcont.style.display = "block";
    }
    
    function showNotLoggedIn() {
        
        var cont = document.getElementById("formcontainer");
        cont.innerHTML = "Du musst eingeloggt sein, um ein Bild hochzuladen. Jetzt <a href='loginForm.php'>Einloggen</a> oder <a href='registrationForm.php'>Registrieren</a>!";
        cont.style.color = "white";
        cont.style.padding = "16px";
        cont.style.fontSize = "14pt";
        cont.style.fontWeight = 500;
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

    /*

        onkeyup="onkeyup_check(event)"

        function onkeyup_check(e) { 

         var keyCode = e.keyCode || e.which; 
         if(keyCode == 13) { 
             e.preventDefault(); 
             alert("nibba"); 
             return false; 
        } 
        }*/

</script>

    <?php 
    
function getlinksindropdown() {
    
    if(isset($_SESSION["session_user_ID"]) && !empty($_SESSION["session_user_ID"])) {
        echo '<a href="user.php?id='.$_SESSION["session_user_ID"].'">Profil</a>';
        echo '<a href="../control/logout.php">Ausloggen</a>';
    } else {
     echo '<a href="loginForm.php">Einloggen</a><a href="registrationForm.php">Registrieren</a>';
    }
}

    ?>
    
</html>
