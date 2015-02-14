<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 13/02/2015
 * Time: 22:38
 */

    //Edits a single item in the menu database. This will be only on the restaurant side of things so no android required.
    require("config.inc.php");

    if (isset($_POST['id'])) {
        //the form was submitted by the user

        //The initial insert query without the parameters, protection from SQL injection
        $query = "DELETE FROM menu WHERE item_id = :id";

        $query_params[":id"] = $_POST['id'];


        //Made it this far then try to insert the data:
        try {
            //try to execute the query.

            //prepare the statement without the parameters.
            $stmt = $db->prepare($query);

            //execute the query with the parameters
            $result = $stmt->execute($query_params);

        }
        catch (PDOException $ex) {
            //Error variable to be passed back via URL and read at the other end.
            $error = "Database error, could not submit";
            echo $error . $ex->getMessage();
            exit;
        }

        header("Location: ../menu.php");
        exit;
    }
    else {
        //If navigated to this page with out pushing the submit button then redirect
        header("Location: ../menu.php");
        exit;
    }