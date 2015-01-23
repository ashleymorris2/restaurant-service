<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 19/01/2015
 * Time: 16:30
 */

    include "../../phpqrcode/qrlib.php";
    include ("config.inc.php");

    $query = "SELECT name FROM restaurant WHERE restaurant_id = 1";

    //Execute the query
    try {
        //Prepare the query:
        $stmt = $db->prepare($query);

        //Add the parameters:
        $result = $stmt->execute();
    }

    catch (PDOException $ex) {
        $response['success'] = 0;
        $response['message'] = "Error connecting to the database";
        echo json_encode($response);
    }

    $row = $stmt->fetch();
    $restaurant = $row["name"];
    $table_num = $_GET['table_number'];


   QRcode::png("id::1::restaurant::" .$restaurant. "::table::" .$table_num);


?>