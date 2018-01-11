<!DOCTYPE html>
<html>

<head>
    <title>toomanyimages</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <section id="uploadContainer">
        
        <form id="imageUploadForm" action = "imageUpload.php" method="post" enctype="multipart/form-data">
            
            <input type="file" id="imageToUpload" name="imageToUpload" style="display: none;"/>
            
            <div id="uploadButtonContainerField">
                <button type="button" id="upload_image_button" onclick="document.getElementById('imageToUpload').click();">Hochladen</button>
            </div>
            
            <input name="img_name" type="text">Name<br>
            <input name="img_desc" type="text">Beschreibung<br>
            <input name="img_private" type="checkbox">Bild privat hochladen<br>
            <input type="submit" name="submit" value="submit">
        </form>
        
    </section>
</body>
</html>