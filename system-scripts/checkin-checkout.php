<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 21/01/2015
 * Time: 00:55
 */

    require("config.inc.php");

    if(!empty($_POST)){

        /**
         * The user is checking into the system so update the records accordingly.
         */
        if($_POST['button_pressed'] = "check_in") {

            //Give it the table number and the customer id a
            $query = "UPDATE resturant_tables
                  SET customer_id = :cusomer_id,
                  status = :status
                  WHERE table_number = :table_number";

            $query_params[':customer_id'] = $_POST['customer_id'];
            $query_params[':table'] = $_POST['table'];

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