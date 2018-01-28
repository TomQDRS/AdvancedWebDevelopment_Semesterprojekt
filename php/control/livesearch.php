<?php


$servername = "localhost";
$db_username = "php_projekt";
$db_password = "php_projekt";
$dbname = "awd_projekt";

$frominput = false;
//get the parameter from URL
$taginput = $_GET["q"];
if(isset($_GET["input"]) && $_GET["input"] == 1) {

    $frominput = true;

}

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

$isexact = false;
$response = "";
$responseAddendum = "";


while($row = $result->fetch_assoc()) {
    if($row["NAME"] == $taginput) {
        $isexact = true;
    }
    $responseAddendum .= "<div onclick='addtag(\"".$row['NAME']."\")'>".$row['NAME']."</div>";
}


if($isexact == false && $frominput) {
    $response .= "<div style='font-style: italic;' onclick='addtag(\"".$_GET["q"]."\")'>Tag erstellen</div>".$responseAddendum;
} else {
    $response .= $responseAddendum;
}


echo $response;

?>
