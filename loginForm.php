<!DOCTYPE html>
<html>

<head>
    <title>toomanyimages - login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <section id="loginContainer">
        <form id="loginForm" action = "loginUser.php" method="post">    
            <input name="usr_login" type="text" placeholder="Nutzername" required>Nutzername oder Email<br>
            <input name="usr_password" type="password" id="firstPassword" required>Passwort<br>
            <input name="submit" type="submit"value="submit">
        </form>
    </section>
</body>
</html>