<?php
    /**
     * Created by PhpStorm.
     * User: Ashley Morris
     * Date: 15/12/2014
     * Time: 14:48
     *
     * will get a single item that is currently in the database.
     */

    //connect to the database
    require("config.inc.php");

    //the initial query
    $query = "SELECT * FROM menu WHERE item_id = :id";

    //Gets the id from http GET
    $query_params[":id"] = $_GET['id'];

    //Execute the query
    try {
        //Prepare the query:
        $stmt = $db->prepare($query);

        //Add the parameters:
        $result = $stmt->execute($query_params);
    }

    catch (PDOException $ex) {
        $response['success'] = 0;
        $response['message'] = "Error connecting to the database";
        echo json_encode($response);
    }

    //Use fetch all to retrieve all the found rows into an array
    $rows = $stmt->fetchAll();

    //If the query has returned any results then we will put it into an array and parse into JSON and out put via html
    if ($rows) {

        $response['success'] = 1;
        $response['message'] = "Connected successfully";

        //says that the key 'items' is an array.

        foreach ($rows as $row) {
            $item = array();

            $item['item_id'] = $row['item_id'];
            $item['name'] = $row['name'];
            $item['price'] = $row['price'];
            $item['description'] = $row['description'];
            $item['category'] = $row['category'];
            $item['stock'] = $row['stock'];

            //update our response with json data, pushes this item onto the items array.
            $response['item'] = $item;
        }

        echo(json_encode($response));
    }
     else {
        $response['success'] = 0;
        $response['message'] = "No data found";
        echo(json_encode($response));
    }
?>