<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 11/01/2015
 * Time: 23:37
 */

    require("config.inc.php");

    //If the http post data isn't empty then search for the customer by username
    if(!empty($_POST)){


        /**If either the password or username is empty when the form is submitted
        then kill the page with an error message. Will do a front end form validation,
        on the android app as well. This backend is just a double check.
         **/
        if(empty($_POST['username']) || empty($_POST['password'])){

            $response['success'] = 0;
            $response['message'] = "Enter both a Username and a Password to continue";

            //Kill page and not execute any more code.
            die(json_encode($response));

        }


        $query = "SELECT customer_id, username, password FROM
                  customers WHERE username = :username";

        //Get the user name parameter from the http post
        $query_params = array(':username' => $_POST['username']);

        try{

            //Prepare the query for execution.
            $stmt = $db->prepare($query);

            //Execute with the additional parameters.
            $result = $stmt->execute($query_params);
        }

        catch(PDOException $ex){

            $response['success'] = 0;
            $response['message'] = "Database error: ".$ex->getMessage();

            die(json_encode($response));
        }

        $valid = false;

        $row = $stmt->fetch();

        //If row is true then the users records have been found.
        if($row){

            //Hash the user provided password using MD5. MD5 hashes always lead to the same value so we can compare
            //the two hashes.
            $raw_password = $_POST['password'];
            $hashed_password = md5($raw_password);

            if($hashed_password == $row['password']){
                $valid = true;
                $response['success'] = 1;
                $response['message'] = "Logged in successfully as " . $row['username'];
                die(json_encode($response));
            }
            else{
                //Password fail
                $response['success'] = 0;
                $response['message'] = "Invalid credentials";
                die(json_encode($response));
            }
        }

        //Username fail
        $response['success'] = 0;
        $response['message'] = "Invalid credentials";
        die(json_encode($response));

    }

?>