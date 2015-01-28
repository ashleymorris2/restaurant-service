<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 19/01/2015
 * Time: 15:59
 */

    //Gets all the restaurant tables that are currently in the database in table number order.

    require("config.inc.php");


        $query = "SELECT * FROM resturant_tables ORDER BY table_number ASC";

        //Execute the query.
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

        //If the query has returned any results then put it into an array and parse into JSON and out put via html
        if ($rows) {
            $response['success'] = 1;
            $response['message'] = "Connected successfully";

            //says that the key 'items' is an array.
            $response['tables'] = array();
            /*
             * For every loop iteration, the value of the current array element ($rows) is
             * assigned to $row and the array pointer is moved by one,
             * until it reaches the last array element.
             */
            foreach ($rows as $row) {
                $table = array();

                $table['table_number'] = $row['table_number'];
                $table['customer_id'] = $row['customer_id'];
                $table['status'] = $row['status'];

                //update our response, pushes this table onto the tables array.
                array_push($response['tables'], $table);
            }

            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "No data found";
            echo json_encode($response);
        }


