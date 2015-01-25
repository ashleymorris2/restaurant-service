<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 19/01/2015
 * Time: 15:20

/*Adds a new table to the database. Table number can be supplied but needs to be unique
   this means numbers can be skipped if necessary. 1,2,3,5 for example. As this will only be accessed via a browser
 and not a phone app, web redirection will be used instead of JSON data.*/

    require("config.inc.php");

    if(isset($_POST['submit'])){
        //The form was user submitted

        if($_POST['table_number'] == 0){
            $error = "The table number has to be greater than 0.";
            header("Location: ../edit-tables.php?e=" . urlencode($error));
            exit;
        }

        //Check to see if the table already exists:
        $query = "SELECT 1 FROM resturant_tables WHERE table_number = :table_number";

        //Query params is an associative array that gets all of the data from the HTTP post array.
        $query_params[':table_number'] = $_POST['table_number'];

        try{
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex){
            //Error variable to be passed back via URL and read at the other end. Remove "$ex->get" message in release.
            $error = "Database error, could not submit. Reason: (". $ex->getMessage(). ")";
            header("Location: ../edit-tables.php?e=" . urlencode($error));
            exit;
        }

        //Data has been returned this means that the table already exists
        $row = $stmt->fetch();
        if($row){
            $error = "That table number is already in use.";
            header("Location: ../edit-tables.php?e=" . urlencode($error));
            exit;
        }

        //Clear to proceed:
        $query = "INSERT INTO resturant_tables (table_number, customer_id, status) VALUES (:table_number, NULL, NULL)";

        try{
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex){
            //Error variable to be passed back via URL and read at the other end. Remove "$ex->get" message in release.
            $error = "Database error, could not submit. Reason: (". $ex->getMessage(). ")";
            header("Location: ../edit-tables.php?e=" . urlencode($error));
            exit;
        }

        $success = "{$query_params[':table_number']} has been added successfully";
        header("Location: ../edit-tables.php?s=" . urlencode($success));
        exit;

    }
    else {
        //If navigated to this page with out pushing the submit button then redirect
        header("Location: ../edit-tables.php");
        exit;
    }



    ?>