<!DOCTYPE html>
<html>

<head>
    <title>toomanyimages</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav>
        <img src="logos/imgup.png" alt="imgup logo" height="36" width="128" id="logo_nav_img" onclick="document.location.href='index.php'">
    </nav>

    <section id="uploadContainer">
        
        <form id="imageUploadForm" action = "imageUpload.php" method="post" enctype="multipart/form-data">
            
            <input type="file" id="imageToUpload" name="imageToUpload" style="display: none;"/>
            
            <div id="uploadButtonContainerField">
                <button type="button" id="upload_image_button" onclick="document.getElementById('imageToUpload').click();">Hochladen</button>
            </div>
            <br>Name
            <input name="img_name" type="text" placeholder="Name" required> <br><br>Beschreibung
            <input name="img_desc" type="text" placeholder="Beschreibung"> <br><br>Bild privat hochladen
            <input name="img_private" type="checkbox" value="private"><br><br><!--Tag
            <input id="img_tag_input"  type="text" placeholder="Tag suchen oder erstellen..."><br><br>
            <div id="tagcontainer">
            
            </div>
            <br><br>-->
            
            <input type="submit" name="submit" value="submit">
        </form>
    </section>
</body>
    

<script type="text/javascript">
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