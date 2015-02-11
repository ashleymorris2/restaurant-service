<?php
    /**
     * Created by PhpStorm.
     * User: Ashley Morris
     * Date: 10/02/2015
     * Time: 20:40
     */

    require("config.inc.php");

    //Deal with post requests, takes a query parameter that limits the number of returned results
    if (isset($_POST)) {

        $return_limit = $_POST['limit'];

        $query = "SELECT * FROM orders WHERE customer_id = :customer_id
              ORDER BY order_date DESC LIMIT $return_limit";

        $query_params[':customer_id'] = $_POST["customer_id"];

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

        if ($rows) {
            $response['success'] = 1;
            $response['message'] = "Connected successfully";

            //Element 'orders' is an array.
            $response['orders'] = array();

            foreach ($rows as $row) {
                $order = array();

                $order['order_id'] = $row['order_id'];
                $order['customer_id'] = $row['customer_id'];
                $order['restaurant_name'] = $row['restaurant_name'];
                $order['order_date'] = $row['order_date'];
                $order{'items_info'} = $row['items_info'];
                $order['order_total'] = $row['order_total'];
                $order['payment_status'] = $row['payment_status'];
                $order['table_number'] = $row['table_number'];


                array_push($response['orders'], $order);
            }

            echo json_encode($response);
        }
        else {
            $response['success'] = 0;
            $response['message'] = "No data found";
            echo json_encode($response);
        }
    }