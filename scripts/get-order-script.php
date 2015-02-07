<?php
    /**
     * Created by PhpStorm.
     * User: Ashley Morris
     * Date: 06/02/2015
     * Time: 21:05
     */

    /**
     *
     * Gets orders from the orders database.
     *
     * Two methods in one script.
     *
     * The GET method only returns the most recent method by table number.
     *
     * The POST method returns upto the specified limit by customer id.
     *
     */
    require("config.inc.php");

    //Deal with GET requests
    if (isset($_GET)) {

        //the initial query
        $query = "SELECT * FROM orders WHERE table_number = :table_number
                  ORDER BY order_date DESC LIMIT 1";

        $query_params[':table_number'] = $_GET['table_number'];

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch (PDOException $ex) {
            //Error variable to be passed back via URL and read at the other end.
            $response['success'] = 0;
            $response['message'] = "Error connecting to the database";
            echo json_encode($response);
        }

        $rows = $stmt->fetchAll();

        //If the query has returned any results then we will put it into an array and parse into JSON and out put via html
        if ($rows) {

            $response['success'] = 1;
            $response['message'] = "Connected successfully";

            //says that the key 'items' is an array.

            foreach ($rows as $row) {
                $order = array();

                $order['order_id'] = $row['order_id'];
                $order['customer_id'] = $row['customer_id'];
                $order['order_date'] = $row['order_date'];
                $order['items_info'] = $row['items_info'];
                $order['payment_status'] = $row['payment_status'];
                $order['table_number'] = $row['table_number'];


                //update our response with json data, pushes this item onto the items array.
                $response['order'] = $order;
            }

            echo(json_encode($response));
        } else {
            $response['success'] = 0;
            $response['message'] = "No data found";
            echo(json_encode($response));
        }
    }
?>