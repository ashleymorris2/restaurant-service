<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 28/01/2015
 * Time: 19:33
 *
 *Returns all the items that are associated with a given category
 *
 *
 */
    //Connection script
    require("config.inc.php");

    if(!empty($_POST)){

        $query = "SELECT * FROM menu WHERE category = :category ORDER BY price ASC";
        $query_params[':category'] = $_POST['category'];

        try{
            $stmt = $db->prepare($query);
            $result= $stmt->execute($query_params);
        }
        catch(PDOException $ex){
            $response['success'] = 0;
            $response['message'] = "Error connecting to the database";
            die (json_encode($response));
        }

        $rows = $stmt->fetchAll();

        if($rows){

            $response['success'] = 1;
            $response['message'] = "Connected to the database successfully";

            //An array to hold the retrieved results
            $response['items'] = array();

            foreach($rows as $row){
                $item = array();

                $item['name'] = $row['name'];
                $item['price'] = $row['price'];
                $item['description'] = $row['description'];

                //Only the system needs to know about the stock or itemID, will return an error if user tries to
                //buy something that isn't in stock.

                array_push($response['items'], $item);
            }

            die(json_encode($response));

        }
        else{

            $response['success'] = 0;
            $response['message'] = "No data found";
            die(json_encode($response));
        }
    }