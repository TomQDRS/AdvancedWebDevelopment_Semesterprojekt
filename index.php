<!DOCTYPE html>
<html>

<head>
    <title>toomanyimages</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav>
            <button type="button" class="nav_button" id="upload_nav_button" onclick="document.location.href='uploadForm.php'" />
        <button class="nav_button" id="search_nav_button"></button>
        <img src="logos/imgup.png" alt="imgup logo" height="36" width="128" id="logo_nav_img">
        <button class="nav_button" id="login_nav_button"></button>
    </nav>
    <section id="main">
        <div class="minibar">
            Sortieren nach:
            <select>
                    <option value="recent">Neueste</option>
                    <option value="name">Name</option>
                    <option value="size">Größe</option>
                </select>
        </div>
        <div id="imagecontainer">
            <?php require('loadImages.php')?>
        </div>
    </section>
    <footer>    
        <a href="impressum.html" class="footer_link">Impressum</a>
    </footer>
</body>

</html>
