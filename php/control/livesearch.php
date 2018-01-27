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
    echo "CONNECTION ERROR";
    return;
}

//Get row where username or email exists
$sql = "SELECT `NAME` FROM `tag` WHERE `NAME` LIKE '".$taginput."%'";

$result = $conn->query($sql);

$response = "";

while($row = $result->fetch_assoc()) {
    
    $response .= "<div onclick='addtag(\"".$row['NAME']."\")'>".$row['NAME']."</div>";
}

echo $response;

?>
