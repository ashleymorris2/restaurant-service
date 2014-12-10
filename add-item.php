<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Item</title>
    <link href="dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>

<?php
    /**
     * Created by PhpStorm.
     * User: Ashley Morris
     * Date: 04/12/2014
     * Time: 11:52
     */
    //Adds a single item to the menu database. This will be only on the restaurant side of things so no android required.
    require("config.inc.php");

    if (isset($_POST['submit'])) {
        //the form was submitted by the user

        //The initial insert query without the parameters, protection from SQL injection
        $query = "INSERT INTO menu (name, price, description, category, stock)
                        VALUES (:name, :price, :description, :category, :stock)";

        /*Query_params is an array that contains all the parameters for the SQL statement.
         it gets the parameters from the HTML post request that is sent with the webform.*/
        $query_params[':name'] = $_POST['item_name'];
        $query_params[':price'] = $_POST['item_price'];
        $query_params[':description'] = $_POST['item_description'];
        $query_params[':category'] = $_POST['item_category'];
        $query_params[':stock'] = $_POST['item_stock'];

        try{
            //try to execute the query.

            //prepare the statement without the parameters.
            $stmt = $db->prepare($query);

            //execute the query with the parameters
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex) {
            //Associative array response to be passed on as json.
            $response['success'] = 0;
            $response['message'] = "Database error, could not submit";
            die(json_encode($response));
        }

        $response['success'] = 1;
        $response['message'] = "{$query_params[':name']} added successfully";
        echo(json_encode($response));// A simple echo for now
    }

    else {



    }
?>

<form action="add-item.php" method="post">
    Item Name:<br>
    <input type="text" name="item_name" placeholder="Item Name" />
    <br><br>
    Price:<br>
    <input type="text" name="item_price" placeholder="Price" />
    <br><br>
    Description:<br>
    <textarea name="item_description" rows="5" cols="23" placeholder="Description"> </textarea>
    <br><br>
    Category:<br>
    <input type="text" name="item_category" placeholder="Category" />
    <br><br>
    Stock:<br>
    <input type="text" name="item_stock" placeholder="Stock" />
    <br><br>
    <input type="submit" name="submit" value="Add Item" />
</form>



</body>
</html>