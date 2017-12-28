<?php
    if(isset($_POST["submit"])) {

        $target_dir = "uploaded_images/";
        $target_file = $target_dir.basename($_FILES["selectedImage"]["name"]);
        $uploadOk = 1;

        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["selectedImage"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image -  ".$check["mime"].".";
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Sorry, only JPG, JPEG & PNG files are allowed for now.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["selectedImage"]["tmp_name"], $target_file)) {
                echo "The file ".basename( $_FILES["selectedImage"]["name"])." has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
?>
