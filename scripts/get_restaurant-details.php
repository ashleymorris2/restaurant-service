<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 20/01/2015
 * Time: 15:27
 */


    //connect to the database
    require("config.inc.php");

    //Currently only one restaurant will be in the system, details are just for storage purposes
    $query = "SELECT * FROM restaurant WHERE restaurant_id = 1";

    //Execute the query
    try {
        //Prepare the query:
        $stmt = $db->prepare($query);

        //Execute
        $result = $stmt->execute();
    }
    catch (PDOException $ex) {
        $response['success'] = 0;
        $response['message'] = "Error connecting to the database";
        echo json_encode($response);
    }

    $row = $stmt->fetch();
    if($row){
        $restaurant = array();

        $response['success'] = 1;
        $response['message'] = "Connected successfully";

        $restaurant['name'] = $row['name'];
        $restaurant['address'] = $row['address'];
        $restaurant['phone_number'] = $row['phone_number'];
        $restaurant['open_time'] = $row['open_time'];
        $restaurant['close_time'] = $row['close_time'];


       $response['restaurant'] = $restaurant;
        die(json_encode($response));
    }
    else {
        $response['success'] = 0;
        $response['message'] = "No data found";
        echo(json_encode($response));
    }





