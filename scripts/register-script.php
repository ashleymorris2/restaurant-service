<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 10/01/2015
 * Time: 22:59
 */

    //Connect to the database
    require("config.inc.php");

    if (!empty($_POST)) {
        //If http post isn't empty then continue:

        /**If either the password or username is empty when the form is submitted
            then kill the page with an error message. Using die isn't best practice. Will do a front end form validation,
            on the android app. This backend is just a double check.
         **/
        if(empty($_POST['username']) || empty($_POST['password'])){

            $response['success'] = 0;
            $response['message'] = "Enter both a Username and a Password to continue";

            //Kill page and not execute any more code.
            die(json_encode($response));

        }

        //If the page hasn't died so far, now need to check if the username already exists in the database.
        $query = "SELECT 1 FROM customers WHERE username = :user";

        //Get the query parameters from the http post submission. "username"
        $query_params = array(':user' => $_POST['username']);

        try{
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $sex){

            $response['success'] = 0;
            $response['message'] = "Database error, please try again";
            die(json_encode($response));
        }

        //If any data is returned then the username already exits.
        $row = $stmt->fetch();
        if($row){
            $response['success'] = 0;
            $response['message'] = "That username is already in use, please try another";
            die(json_encode($response));
        }

        //Clear to try and create a new user at this point.
        $raw_password = $_POST['password'];

        //Hash the password using MD5, not the most secure method (able to brute force) but better than nothing.
        $hashed_password = md5($raw_password);

        $query = "INSERT INTO customers (username, password) VALUES (:user, :password)";

        $query_params[":user"] = $_POST['username'];
        $query_params['password'] = $hashed_password;

        try{
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex){
            //Error has occurred, kill with an error message.
            $response['success'] = 0;
            $response['message'] = "Database error. Error registering new user, try again.";
            die(json_encode($response));
        }

        //Finally the user has been added successfully, display a success message for the user.
        $response['success'] = 1;
        $response['message'] = "Registration successful";
        echo(json_encode($response));









    }
?>