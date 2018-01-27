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
        <title>
            <?php getImageName(); ?> - toomanyimages</title>
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
                <div id=image_details>
                    
                                    <div id="image_detail_view"><img src="<?php loadImage(); ?>"></div>

                    
                    <div id="image_detail_name">
                        <?php getImageName(); ?>
                    </div>
                    <div id="image_detail_description">
                        <?php getImageDescription(); ?>
                    </div>
                    <div id="image_detail_meta">
                        <div id="user_for_image">Hochgeladen von:
                            <?php getUserForImage(); ?>
                        </div>
                        <div id="image_uploaded_on">Hochgeladen am:
                            <?php getImageUploadedOn(); ?>
                        </div>
                        <br>
                        <div class="tagarea">
                            <div id="tagdisplay"><?php getTagsForImage();?></div>
                        </div>
                    </div>
                </div>   
                    <button onclick="toggleTagInput()">Tags ändern</button>
                    <input id="tag_input_field" style="display:none;" onkeyup="checkfortaginput(this)" type="text" placeholder="Tag erstellen oder suchen...">
                    <div id="livesearch"></div>
            <br>

            <div class="commentarea">
                <?php getCommentsForImage(); ?>
                <div id="commentinput">
                    <!--<input id="commentinputfield" type="text" name="comment" placeholder="Kommentar schreiben...">-->
                    <!--<textarea name="comment" id="commentinputfield" placeholder="Kommentar schreiben..."></textarea>-->
                    <span contenteditable="true" id="commentinputfield" name="comment" placeholder="Kommentar schreiben..."></span>
                    <button id="sendcommentbutton" onclick="validatecomment();" value="Kommentieren">Kommentieren</button>
                </div>
            </div>
        </section>
        <footer>
            <a href="impressum.html" class="footer_link">Impressum</a>
        </footer>
    </body>

    <script type="text/javascript">
        
        window.onload = function () {
            
           if(<?php if(isset($_SESSION["session_user_ID"]) && !empty($_SESSION["session_user_ID"])) {
                echo false; 
            } else {
                echo true;
            }
              ?>)     {
               var comminp = document.getElementById("commentinputfield");
            var buttonComment = document.getElementById("sendcommentbutton");
            
            buttonComment.disabled = true;
            comminp.contentEditable = false;
            comminp.innerHTML = "Bitte melde Dich an, um einen Kommentar zu schreiben.";
               
               }
            
        }
        function toggleTagInput() {

            var x = document.getElementById("tag_input_field");
            if (x.style.display == "none") {
                x.style.display = "inline";
            } else {
                x.style.display = "none";
            }
        }

        function addtag(tag) {


        }

        function postComment(comment) {

            document.getElementById("sendcommentbutton").disabled = true;


            console.log("got there");

            var xmlhttp = new XMLHttpRequest();

            var url = "postcomment.php";
            var request = "";

            request += "user=" + "<?php if(isset($_SESSION["session_user_ID"])) {echo $_SESSION["session_user_ID"];} else {echo 0;} ?>";
            request += "&image=" + "<?php echo $_GET["id"];?>";
            request += "&comment=" + encodeURIComponent(comment);

            xmlhttp.open("POST", url, true);

            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xmlhttp.onreadystatechange = function() { //Call a function when the state changes.
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    location.reload();
                }
            }

            xmlhttp.send(request);
        }

        function validatecomment() {
            var cmtinput = document.getElementById("commentinputfield");
            var text = cmtinput.innerHTML;

            var textvalid = false;

            while (textvalid == false) {

                textvalid = true;

                if (text.startsWith("<div><br></div>")) {
                    console.log(text);
                    text = text.replace("<div><br></div>", "");
                    textvalid = false;
                }

                if (text.startsWith("<br>")) {
                    text = text.replace("<br>", "");
                    textvalid = false;

                }

                if (text.startsWith(" ")) {
                    text = text.replace(" ", "");
                    textvalid = false;

                }

                if (text.startsWith("&nbsp;")) {
                    text = text.replace("&nbsp;", "");
                    textvalid = false;

                }


                if (text.endsWith("<div><br></div>")) {
                    console.log(text);
                    text = text.replace(new RegExp("<div><br></div>$"), "");
                    textvalid = false;
                }

                if (text.endsWith("<br>")) {
                    text = text.replace(new RegExp("<br>$"), "");
                    textvalid = false;

                }

                if (text.endsWith(" ")) {
                    text = text.replace(new RegExp(" $"), "");
                    textvalid = false;

                }

                if (text.endsWith("&nbsp;")) {
                    text = text.replace(new RegExp("&nbsp;$"), "");
                    textvalid = false;

                }

                if (text.endsWith("<div>")) {
                    text = text.replace(new RegExp("<div>$"), "");
                    textvalid = false;

                }


            }

            if (text.length != 0) {
                postComment(text);
            }

        }

        function checkfortaginput(taginput) {

            input = taginput.value;
            console.log(input);

            if (input.length == 0) {

                document.getElementById("livesearch").innerHTML = "";
                document.getElementById("livesearch").style.border = "0px";
                return;
            }

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("livesearch").innerHTML = this.responseText;
                    document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
                }
            }
            xmlhttp.open("GET", "livesearch.php?q=" + input, true);
            xmlhttp.send();
        }

    </script>

    <?php

    function getTagsForImage() {
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
        $sql = "SELECT `tag`.`NAME`, `tag`.`ID` FROM `tag` INNER JOIN `image_tag` ON `tag`.`ID` = `image_tag`.`TAG_ID` WHERE `image_tag`.`IMAGE_ID` = ".$_GET["id"]."";

        $result = $conn->query($sql);
        
        while($row = $result->fetch_assoc()) {
            echo "<a href='index.php?tag=".$row["ID"]."'>";
            echo "#".$row["NAME"];
            echo "</a>";
        }
    }
    
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
    $sql = "SELECT `user`.`USERNAME`, `user`.`ID` FROM `user` INNER JOIN `image` ON `image`.`IMG_USER_ID` = `user`.`ID` WHERE `image`.`ID` = '".$_GET["id"]."'";
    
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        echo "<a href='user.php?id=".$row["ID"]."'>";
        echo $row["USERNAME"];
        echo "</a>";
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
    
function getCommentsForImage() {
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
    $sql = "SELECT `CONTENT`, `POSTED_ON`, `user`.`USERNAME`, `user`.`ID` FROM `comment` INNER JOIN `user` ON `CMT_USER_ID`= `user`.`ID` WHERE `CMT_IMAGE_ID` = '".$_GET["id"]."' ORDER BY `POSTED_ON` ASC";
    
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        
        $date = explode(" ", $row["POSTED_ON"]);
        
        echo "<div class = 'comment'>"
            ."<div class = 'comment_userinfo'>"
            ."<img src='profile_images/standard/standard-150.png'>"
            ."<a href='user.php?id=".$row["ID"]."'>".$row["USERNAME"]."</a>"
              ."<div>".$date[0]."</div>"
            ."</div>"
            ."<span>".$row["CONTENT"]."</span>"
            ."</div>";
        
    }
}
            
?>

</html>
