<?php
    /**
     * Created by PhpStorm.
     * User: Ashley Morris
     * Date: 04/12/2014
     * Time: 11:52
     */
    //Edits a single item in the menu database. This will be only on the restaurant side of things so no android required.
    require("config.inc.php");

    if (isset($_POST['submit'])) {
        //the form was submitted by the user

        //The initial insert query without the parameters, protection from SQL injection
        $query = "UPDATE restaurant
                  SET
                  name = :name,
                  address = :address,
                  phone_number = :phone_number,
                  open_time = :open_time,
                  close_time = :close_time
                  WHERE restaurant_id = 1";

        /*Query_params is an array that contains all the parameters for the SQL statement.
         it gets the parameters from the HTML post request that is sent with the webform.*/
        $query_params[':name'] = $_POST['name'];
        $query_params[":address"] = $_POST['address'];
        $query_params[':phone_number'] = $_POST['phone_number'];
        $query_params[':open_time'] = $_POST['open_time'];
        $query_params[':close_time'] = $_POST['close_time'];

        //Validate information here and redirect to form if necessary




        //If the error variable is set then relocate back to this page with an error message.
        if (isset($error)) {
            header("Location: ../edit-restaurant.php?e=" . urlencode($error));
            exit;
        }

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
            $error = "Database error, could not submit" . $ex->getMessage();
            header("Location: ../edit-restaurant.php?e=" . urlencode($error));
            exit;
        }

        $success = "{$query_params[':name']}, has been updated successfully.";
        header("Location: ../edit-restaurant.php?e=" . urlencode($error));
        exit;
    } else {

        //If navigated to this page with out pushing the submit button then redirect
        header("Location: ../edit_restaurant.php");
        exit;
    }
?>
