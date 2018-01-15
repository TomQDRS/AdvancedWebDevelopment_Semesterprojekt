<!DOCTYPE html>
<html>

<head>
    <title>toomanyimages - register</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <section id="registerContainer">
        <form id="registrationForm" action = "registerUser.php" method="post">    
            <input name="usr_name" type="text" placeholder="Nutzername" oninput="checkForValidUsername(this)" required>Nutzername<br>
            <input name="usr_mail" type="email" placeholder="Email" required>E-Mail<br>
            <input name="usr_password" type="password" id="firstPassword" oninput="checkForValidPassword(this)" required>Passwort<br>
            <input name="usr_password_confirm" type="password" id="password_confirm" oninput="checkForPasswordMatch(this)" required>Passwort Wiederholen<br>
            <input name="submit" type="submit"value="submit">
        </form>
    </section>
</body>
</html>

<!-- Javascript functions for validating form input -->
<script language='javascript' type='text/javascript'>
    
    /** CHECK FOR VALID PASSWORD - created by Tom Quinders
    *
    * This function checks if the usr_password input field contains a 
    * password that is longer than 6 characters, for safety reasons.
    *
    * It is called when the usr_password input receives a user
    * input and sets the "custom validity" of HTML 5 to a certain value
    * that will prevent the form from submitting if it is set.
    */
    function checkForValidPassword(input) { 
        if (input.value.length <= 5) {
            //Input is invalid
            input.setCustomValidity('Passwort muss mindestens 6 Zeichen lang sein.');
        } else {
            //Input is valid
            input.setCustomValidity('');
        }
    }
    
    /** CHECK FOR PASSWORD MATCH - created by Tom Quinders
    *
    * This function checks if the usr_password and usr_password_confirm
    * input fields contain the same value. This is done to check if the
    * user made an error when inputting their password. 
    *
    * It is called when the usr_password_confirm input receives a user
    * input and sets the "custom validity" of HTML 5 to a certain value
    * that will prevent the form from submitting if it is set.
    */
    function checkForPasswordMatch(input) {
        if (input.value != document.getElementById('firstPassword').value) {
            //Input is invalid
            input.setCustomValidity('Passwörter sind nicht gleich.');
        } else {
            //Input is valid
            input.setCustomValidity('');
        }
    }

    /** CHECK FOR VALID USER NAME - created by Tom Quinders
    *
    * This function checks if the usr_name input field contains a valid
    * username. It uses a "regular expression" to check the input value
    * against a set of allowed characters.
    *
    * It is called when the usr_name input receives a user input and sets the 
    * "custom validity" of HTML 5 to a certain value that will prevent the form 
    * from submitting if it is set.
    */
    function checkForValidUsername(input) {
        
        /** REGULAR EXPRESSION: /^\w{5,42}$/
        *
        * - ^ asserts position at start of the string
        * - \w{3,42} matches any word character (equal to [a-zA-Z0-9_])
        * - {3,42} Quantifier — Matches between 3 and 42 times, as many times as possible, 
        *   giving back as needed (greedy)
        * - $ asserts position at the end of the string, or before the line terminator right at 
        *   the end of the string (if any)
        *
        * Explanation of expression generated by https://regex101.com/
        */
        var userNameRegex = /^\w{5,42}$/;
        /*console.log(input.value);*/
        
        if(input.value.match(userNameRegex) == null) {
            /*console.log("invalid");*/
            //Input is invalid
            input.setCustomValidity('Der eingegebene Nutzername ist nicht korrekt. Nur Zeichen von "A" bis "Z",  "0" bis "9" und "_" sind erlaubt. Der Name muss länger als 4 Zeichen und kürzer als 42 Zeichen sein.');
        } else {
            //Input is valied
            input.setCustomValidity('');
        }
    }
</script>