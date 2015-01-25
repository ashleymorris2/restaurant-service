<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 24/01/2015
 * Time: 00:55
 *
 * Simply returns a list of the categories that are currently in the menu database
 *
 */
    //connect to the database
    require("config.inc.php");

    //the initial query (select all from menu order by category)
    $query = "SELECT DISTINCT category FROM menu";

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
        $response['categories'] = array();
        /*
         * For every loop iteration, the value of the current array element ($rows) is
         * assigned to $row and the array pointer is moved by one,
         * until it reaches the last array element.
         */
        foreach ($rows as $row) {
            $item = array();

            $item['category'] = $row['category'];

            //update our response with json data, pushes this item onto the items array.
            array_push($response['categories'], $item);
        }

        echo(json_encode($response));
    }

    else {
        $response['success'] = 0;
        $response['message'] = "No data found";
        echo(json_encode($response));
    }
?>