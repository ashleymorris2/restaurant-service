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
        $query = "UPDATE menu
                  SET
                  name = :name,
                  price = :price,
                  description = :description,
                  category = :category,
                  stock = :stock
                  WHERE item_id = :id";

        /*Query_params is an array that contains all the parameters for the SQL statement.
         it gets the parameters from the HTML post request that is sent with the webform.*/
        $query_params[":id"] = $_POST['item_id'];
        $query_params[':name'] = $_POST['item_name'];
        $query_params[':price'] = $_POST['item_price'];
        $query_params[':description'] = $_POST['item_description'];
        $query_params[':category'] = $_POST['item_category'];
        $query_params[':stock'] = $_POST['item_stock'];

        //Validate information here and redirect to form if necessary

        //1: Check for null data entry.
        if (empty($query_params[":name"])) {
            $error = "Item name must be entered.";
        }
        else if (empty($query_params[":price"])){
            $error = "Item price must be entered.";
        }
        else if (empty($query_params[":category"])) {
            $error = "Item category must be entered.";
        }
        else if (empty($query_params[":stock"])) {
            $error = "Item stock must be entered.";
        }

        if (isset($error)) {
            header("Location: ../edit-item.php?e=" . urlencode($error) . "&id=" . urlencode($query_params[':id']));
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
            $error = "Database error, could not submit";
            header("Location: ../edit-item.php?e=" . urlencode($error) . "&id=" . urlencode($query_params[':id']));
            exit;
        }

        $success = "{$query_params[':name']}, has been updated successfully.";
        header("Location: ../edit-item.php?s=" . urlencode($success) . "&id=" . urlencode($query_params[':id']));
        exit;
    } else {

        //If navigated to this page with out pushing the submit button then redirect
        header("Location: ../menu.php");
        exit;
    }
?>
