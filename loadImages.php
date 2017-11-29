<?php 

    $imageFiles = [];
    $uploadDirectory = 'uploaded_images/';

    if(is_dir($uploadDirectory))
    {
        $scanned_directory = scandir($uploadDirectory);

        foreach ($scanned_directory as $file)
        {        
            $ext = pathinfo($file, PATHINFO_EXTENSION);

            if($ext == 'jpg' || $ext == 'png' || $ext == 'gif')
            {
                $imageFiles[] = $file;
            }
        }
    }

    foreach ($imageFiles as $image)
    {
        echo '<img class="uploaded_image" src="uploaded_images/'.$image.'"/>';
    }

?>