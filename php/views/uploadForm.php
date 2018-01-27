<!DOCTYPE html>
<html>

<head>
    <title>toomanyimages</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>
    <nav>
        <img src="../../logos/tmi_logo_text.png" alt="toomanyimages logo" height="36" width="101" id="logo_nav_img" onclick="document.location.href='../../index.php'">
    </nav>

    <section id="formcontainer">

        <form id="imageUploadForm" action="../control/imageUpload.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">

            <input class type="file" id="imageToUpload" name="imageToUpload" style="display: none;"/>

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
        </form>
    </section>
</body>


<script type="text/javascript">
    function validateForm() {
        var inp = document.getElementById('imageToUpload');
        if (inp.files.length === 0) {
            alert("Bitte wähle eine Bilddatei zum Hochladen.");
            return false;
        } else {
            return true;
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

</html>
