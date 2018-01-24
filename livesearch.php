<?php


$servername = "localhost";
$db_username = "php_projekt";
$db_password = "php_projekt";
$dbname = "awd_projekt";

//get the parameter from URL
$taginput = $_GET["q"];

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
//Check if the connection succeeded, otherwise abort
if ($conn->connect_error) {
    //An error occured, return FALSE for error handling
    $errorMessage = "We're sorry, there was an error trying to establish a connection to our database. Please try again later.";
    echo "ERROR";
}

//Get row where username or email exists
$sql = "SELECT `NAME` FROM `tag` WHERE `NAME` LIKE '".$taginput."%'";

$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
     print("<p>".$row['NAME']."<p>");
    
}

?>
