<?php
    /**
     * Created by PhpStorm.
     * User: Ashley Morris
     * Date: 08/02/2015
     * Time: 22:40
     */
    require("config.inc.php");

    //Gets all the restaurant tables that are currently in the database in table number order.
    if (isset($_POST)) {


        $query = "SELECT status_code FROM resturant_tables WHERE table_number = :table_number";

        $query_params[':table_number'] = $_POST['table_number'];

        //Execute the query.
        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch (PDOException $ex) {
            $response['success'] = 0;
            $response['message'] = "Error connecting to the database" . $ex->getMessage();
            echo json_encode($response);
        }

        //Use fetch all to retrieve all the found rows into an array
        $row = $stmt->fetch();

        //If the query has returned any results then put it into an array and parse into JSON and out put via html
        if ($row) {
            $response['success'] = 1;

            $current_status = $row['status_code'];

            $response['status_code'] = $current_status;

            if ($current_status == 0){
                $response['message'] = "Your session has expired.";
            }
            if ($current_status == 4) {
                $response['message'] = "Your food is on its way.";
            }
            if ($current_status == 5){
                $response['message'] = "Your order is complete";
            }

            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "No data found";
            echo json_encode($response);
        }
    }