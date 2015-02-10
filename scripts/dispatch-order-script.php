<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 08/02/2015
 * Time: 19:45
 */

    require("config.inc.php");

    /**
     *
     *Get the table status code so that it can be updated to the correct value.
     *
     * */
    if(isset($_GET)) {

        $status_query = "SELECT status_code FROM resturant_tables
                        WHERE table_number = :table_number";

        $query_params[':table_number'] = $_GET['table_number'];

        try {
            //prepare the statement without the parameters.
            $stmt = $db->prepare($status_query);

            //execute the query with the parameters
            $result = $stmt->execute($query_params);
        }
        catch (PDOException $ex) {
            $response['success'] = 0;
            $response['message'] = "Error connecting to the database. Call 2 : " . $ex->getMessage();

            header("Location: ../dashboard.php");
            exit;
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
                $query_params[':status'] = "Order dispatched (awaiting payment)";
                $query_params['status_code'] = 4;

                $response['order_complete'] = false;
            } //The order has been delivered but payment hasn't been made
            if ($current_status == 3) {
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

                header("Location: ../dashboard.php");
                exit;
            }

            //Finally the payment has gone through successfully, display a success message for the user.
            $response['success'] = 1;
            $response['message'] = "Order has been dispatched";

            header("Location: ../dashboard.php");
            exit;
        }
    }