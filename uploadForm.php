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
            <br>
            <input name="img_name" type="text" placeholder="Name" required> Name<br><br>
            <input name="img_desc" type="text" placeholder="Beschreibung"> Beschreibung<br><br>
            <input name="img_private" type="checkbox" value="private">Bild privat hochladen<br><br>
            <input type="submit" name="submit" value="submit">
        </form>
    </section>
</body>
</html>