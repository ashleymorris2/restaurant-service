<?php
/**
 * Created by PhpStorm.
 * User: Ashley Morris
 * Date: 19/01/2015
 * Time: 16:30
 */

    include "../../phpqrcode/qrlib.php";

    $id = 233;
    $restaurant = "Pizza Punt";
    $table_num = $_GET['table_number'];


   QRcode::png("id::" .$id. "::restaurant::" .$restaurant. "::table::" .$table_num);


?>