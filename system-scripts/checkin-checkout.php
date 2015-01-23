<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 21/01/2015
 * Time: 00:55
 */
    /**
     * ------Status and status codes
     * ------0 = Table is free to use.
     * ------1 = Ready to order
     *
     */

    require("../scripts/config.inc.php");

    if(!empty($_POST)){

        /**
         * The user is checking into the system so update the records accordingly.
         */

        if($_POST['method'] = "check_in") {

            //Check for status code 0 else die with error:
            $query = "SELECT status_code FROM resturant_tables WHERE table_number = :table_number";


            $query_params[':table_number'] = $_POST['table_number'];
            try{
                $stmt = $db->prepare($query);
                $result = $stmt->execute($query_params);
            }
            catch(PDOException $ex){
                $response['success'] = 0;
                $response['message'] = "Database error: " .$ex->getMessage();

                //Kill page and not execute any more code.
                die(json_encode($response));
            }

            $row = $stmt->fetch();
            $status = $row['status_code'];

            if($status != 0){
                //If the status isn't 0 then the table is already in use, kill page with an error.
                $response['success'] = 0;
                $response['message'] = "That table is already in use";

                //Kill page and not execute any more code.
                die(json_encode($response));
            }


            //At this point free to continue
            //Give it the table number and the customer id
            $query = "UPDATE resturant_tables
                  SET customer_id = :customer_id,
                  status = :status,
                  status_code = :status_code
                  WHERE table_number = :table_number";

            $query_params[':customer_id'] = $_POST['customer_id'];
            $query_params[':status'] = "Ready to order";
            $query_params['status_code'] = 1;

            try{
                $stmt = $db->prepare($query);
                $result = $stmt->execute($query_params);
            }
            catch(PDOException $ex){
                $response['success'] = 0;
                $response['message'] = "Database error: " .$ex->getMessage();

                //Kill page and not execute any more code.
                die(json_encode($response));
            }

            $response['success'] = 1;
            $response['message'] = "Checked in successfully";

            //Kill page and not execute any more code.
            die(json_encode($response));
        }


        else if ($_POST['button_pressed'] = "check_out") {


        }

    }