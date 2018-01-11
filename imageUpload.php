<?php

print_r($_FILES);

//FILE UPLOAD
$target_dir = "uploaded_images/";
$target_file = $target_dir . basename($_FILES["imageToUpload"]["name"]);
$uploadOK = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$private = 0;


if(isset($_POST["submit"])) {
    print_r("IS SUBMIT");
    //MARK: Check for Image
    //Check if it's a real file
    $check = getimagesize($_FILES["imageToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image -  ".$check["mime"].".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    
    //Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, this file already exists.";
        $uploadOk = 0;
    }   
    
    // Check file size
    if ($_FILES["imageToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    
    if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["imageToUpload"]["name"]). " has been uploaded.";
            
            if(isset($_POST['img_private']) && $_POST['img_private'] == 'private') {
                $private = 1;
            }
            
            addImageToDB($_POST['img_name'], $_POST['img_desc'],$target_file, 007, $private);
            
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    
}

function addImageToDB($name, $desc, $path, $userid, $private) {
    
    //DATABASE
    $servername = "localhost";
    $username = "php_projekt";
    $password = "php_projekt";
    $dbname = "awd_projekt";

    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    
    $sql = "INSERT INTO image (NAME, DESCRIPTION, PATH, IMG_USER_ID, PRIVATE) VALUES ('".$name."', '".$desc."', '".$path."', ".$userid.", ".$private.")";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    
}
?>
