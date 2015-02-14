<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 12/02/2015
 * Time: 13:35
 */

    require("config.inc.php");

    if (isset($_POST['table_number'])) {

    /**
         *
         *Get the table status code so that it can be updated to the correct value.
         *
         * */
        $status_query = "SELECT status_code FROM resturant_tables
                        WHERE table_number = :table_number";

        $query_params[':table_number'] = $_POST['table_number'];

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
                $query_params[':status'] = "Order placed (request cash payment)";
                $query_params['status_code'] = 6;

                $response['order_complete'] = false;

            } //The order has been delivered but payment hasn't been made
            if ($current_status == 4) {
                $query_params[':status'] = "Order dispatched (request cash payment)";
                $query_params['status_code'] = 7;
            }

            $query_params[':table_number'] = $_POST['table_number'];

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

            //Finally the cash request has gone through
            $response['success'] = 1;
            $response['message'] = "Thank you, some one will be with you in a minute";
            echo(json_encode($response));
        }
    }
