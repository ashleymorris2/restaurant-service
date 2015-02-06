<?php
    /**
     * Created by PhpStorm.
     * User: Ashley Morris
     * Date: 05/02/2015
     * Time: 19:21
     */

    require("config.inc.php");

    if (isset($_POST)) {

        /**
         * This query updates the most recent order at the table to status 2,
         * meaning that the payment has been made.
         * */
        $update_query = "UPDATE orders SET payment_status = 2
                  WHERE table_number = :table_number
                  ORDER BY order_date DESC LIMIT 1";

        $query_params[':table_number'] = $_POST['table_number'];

        try {
            //prepare the statement without the parameters.
            $stmt = $db->prepare($update_query);

            //execute the query with the parameters
            $result = $stmt->execute($query_params);
        }
        catch (PDOException $ex) {
            $response['success'] = 0;
            $response['message'] = "Error connecting to the database. Call 1 : " . $ex->getMessage();
            die (json_encode($response));
        }


        /**
         *
         *Get the table status code so that it can be updated to the correct value.
         *
         * */
        $status_query = "SELECT status_code FROM resturant_tables
                        WHERE table_number = :table_number";

        try {
            //prepare the statement without the parameters.
            $stmt = $db->prepare($status_query);

            //execute the query with the parameters
            $result = $stmt->execute($query_params);
        }
        catch (PDOException $ex) {
            $response['success'] = 0;
            $response['message'] = "Error connecting to the database. Call 2 : " . $ex->getMessage();
            die (json_encode($response));
        }

        $row = $stmt->fetch();

        /**
         * Finally update the table to reflect the changes that have been made to the oder.
         * Using the status code, can find the current status of the table and able to set the new
         * status correctly.
         */
        if ($row) {

            $update_query = "UPDATE resturant_tables
                  SET status = :status,
                  status_code = :status_code
                  WHERE table_number = :table_number";

            $current_status = $row['status_code'];

            //order has been placed but the payment hasn't been made.
            if ($current_status == 2) {
                $query_params[':status'] = "Order placed (received payment)";
                $query_params['status_code'] = 3;

                $response['order_complete'] = false;

            } //The order has been delivered but payment hasn't been made
            if ($current_status == 4) {
                $query_params[':status'] = "Order complete";
                $query_params['status_code'] = 5;
            }

            try {
                //prepare the statement without the parameters.
                $stmt = $db->prepare($update_query);

                //execute the query with the parameters
                $result = $stmt->execute($query_params);
            }
            catch (PDOException $ex) {
                $response['success'] = 0;
                $response['message'] = "Error connecting to the database. Call 3 : " . $ex->getMessage();
                die (json_encode($response));
            }

            //Finally the payment has gone through successfully, display a success message for the user.
            $response['success'] = 1;
            $response['message'] = "Your payment has been received. Thank you.";
            echo(json_encode($response));
        }
    }