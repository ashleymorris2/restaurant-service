<?php
    /**
     * Created by PhpStorm.
     * User: Ashley Morris
     * Date: 04/12/2014
     * Time: 00:00
     *
     * will get a list of all the items that are currently in the database.
     * Will create a separate script that will sync the mySql database with an android
     * SQLlite one so that the user only has to download the menu data once.
     * This second script will send JSON data.

     */

    //connect to the database
    require("config.inc.php");

    //the initial query (select all from menu order by category)
    $query = "SELECT * FROM menu ORDER BY category, price ASC";

    //Execute the query
    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute();
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
        $response['items'] = array();
        /*
         * For every loop iteration, the value of the current array element ($rows) is
         * assigned to $row and the array pointer is moved by one,
         * until it reaches the last array element.
         */
        foreach ($rows as $row) {
            $item = array();

            $item['item_id'] = $row['item_id'];
            $item['name'] = $row['name'];
            $item['price'] = $row['price'];
            $item['description'] = $row['description'];
            $item['category'] = $row['category'];
            $item['stock'] = $row['stock'];

            //update our response with json data, pushes this item onto the items array.
            array_push($response['items'], $item);
        }

        echo(json_encode($response));
    }

    else {
        $response['success'] = 0;
        $response['message'] = "No data found";
        echo(json_encode($response));
    }
?>