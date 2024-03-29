<?php
    /**
     * Created by PhpStorm.
     * User: Ashley Morris
     * Date: 03/02/2015
     * Time: 21:21
     */

    //Generated
    ////Order ID,


    //Server Supplied
    // orderDate, paymentStatus.

    //Client Supplied::
    //Customer ID, itemsInfo, orderTotal, table number.

    //Payment status 1 = order received no payment
    //2 = Payment received

    require("config.inc.php");

    //Was user submitted via http post.
    if (isset($_POST)) {


        //Duplicate order check. If the table is already at status 2 then a recent order has been placed.
        //Do not duplicate.
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


        $row = $stmt->fetch();

        if ($row) {
            $current_status = $row['status_code'];

            if($current_status == 2){

                $response['success'] = 1;
                $response['message'] = "Your order has been placed successfully.";
                echo(json_encode($response));

            }
        }



        //Order placement query
        $query = "INSERT INTO orders (customer_id, restaurant_name, items_info, order_total,
                  table_number, order_date, payment_status)
                  VALUES (:customer_id, :restaurant_name, :items_info, :order_total,
                  :table_number, :order_date, :payment_status)";

        $query_params[':customer_id'] = $_POST['customer_id'];
        $query_params[':restaurant_name'] = $_POST['restaurant_name'];
        $query_params[':items_info'] = $_POST['items_info'];
        $query_params[':order_total'] = $_POST['order_total'];
        $query_params[':table_number'] = $_POST['table_number'];

        //Gets time in milliseconds
        $milliseconds = round(microtime(true) * 1000);
        $query_params[':order_date'] = $milliseconds;
        $query_params[':payment_status'] = 1;


        //Also update the status of the table in the system
        $query2 = "UPDATE resturant_tables
                  SET customer_id = :customer_id,
                  status = :status,
                  status_code = :status_code
                  WHERE table_number = :table_number";

        $query_params2[':customer_id'] = $_POST['customer_id'];
        $query_params2[':status'] = "Order placed (awaiting payment)";
        $query_params2['status_code'] = 2;
        $query_params2['table_number'] = $_POST['table_number'];

        try {

            //prepare the statement without the parameters.
            $stmt = $db->prepare($query);
            $stmt2 = $db->prepare($query2);

            //execute the query with the parameters
            $result = $stmt->execute($query_params);
            $result2 = $stmt2->execute($query_params2);

        }
        catch (PDOException $ex) {
            $response['success'] = 0;
            $response['message'] = "Error connecting to the database. ".$ex->getMessage();
            die (json_encode($response));
        }


        $response['success'] = 1;
        $response['message'] = "Your order has been placed successfully.";
        echo(json_encode($response));
    }