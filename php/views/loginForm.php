<!DOCTYPE html>
<html>

<head>
    <title>toomanyimages - login</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>
    <nav>
        <img src="../../logos/tmi_logo_text.png" alt="toomanyimages logo" height="36" width="101" id="logo_nav_img" onclick="document.location.href='../../index.php'">
    </nav>

    <section id="formcontainer">
        <form id="loginForm" action="../control/loginUser.php" method="post">
            <input class="formtextinput" name="usr_login" type="text" placeholder="Nutzername" required>Nutzername oder Email<br>
            <input class="formtextinput" name="usr_password" type="password" id="firstPassword" placeholder="Passwort" required>Passwort<br>
            <div class="formcheckboxcontainer">
            <input class="formcheckbox" name="usr_rememberme" type="checkbox" value="rememberme">Eingeloggt bleiben</div>
            <input class="formsubmit"name="submit" type="submit" value="Einloggen">
        </form>
        Du hast noch kein Profil? <a href="registrationForm.php">Hier Registrieren!</a>
    </section>
</body>

</html>
