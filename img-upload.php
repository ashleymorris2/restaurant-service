<?php
    /**
     * Created by PhpStorm.
     * User: Ashley Morris
     * Date: 07/02/2015
     * Time: 16:28
     */

    require("scripts/config.inc.php");


    //Update the image location in the restaurant database
    $query = "UPDATE restaurant
                  SET
                  image = :directory
                  WHERE restaurant_id = :id";

    $query_params[':id'] = $_POST['restaurant_id'];

    //Directory to place the file in
    $target_dir = "images/";

    //Path of the file to be uploaded
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

    $uploadOk = 1;

    //Gets the extension type of the file
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    //check supported type
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType
        != "jpeg" && $imageFileType != "gif"
    ) {

        $error = "Image is not of the supported type. Only JPG, JPEG, PNG and GIF allowed.";
        $uploadOk = 0;
    }

    //Check if file is an image
    if (isset($_POST['submit'])) {
        //Temporary path and filename where the file was stored on the server
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

        if ($check != false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }

    //Check if file exists
    if (file_exists($target_file)) {
        $error = "That file already exists on the server.";
        $uploadOk = 0;
    }

    //Check Size
    if ($_FILES["fileToUpload"]["size"] > 3000000) {
        $error = "That file is larger than 3mb.";
        $uploadOk = 0;
    }

    //Redirect with error message
    if ($uploadOk == 0) {
        header("Location: edit-restaurant.php?e=" . urlencode($error));
        exit;
    } //try to move the file from tmp
    else {

        $target_file = str_replace(" ", "_", $target_file);

        //Update database record if success
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {

            $query_params[":directory"] = $target_file;

            try {
                //try to execute the query.

                //prepare the statement without the parameters.
                $stmt = $db->prepare($query);

                //execute the query with the parameters
                $result = $stmt->execute($query_params);
            }
            catch (PDOException $ex) {
                //Error variable to be passed back via URL and read at the other end.
                $success = "Image upload has failed please try again.";
            }

            $success = "The image has been uploaded";
            header("Location: edit-restaurant.php?s=" . urlencode($success));
            exit;

        } else {
            header("Location: edit-restaurant.php");
            exit;
        }
    }

