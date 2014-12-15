<?php
    /**
     * Created by PhpStorm.
     * User: Ashley Morris
     * Date: 29/11/2014
     * Time: 17:07
     */

    //database variables:
    $username = "restaurant";
    $password = "@ngry+w1$+";
    $host = "localhost";
    $dbname = "restaurant_system";

    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

    //UTF-8 is for character encoding, allows for a a wider variety of special characters in the database.
    try {
        /*
         * This tries to open a connection to the database using the PDO library (PHP Data Object)
         * It provides a flexible interface between PHP and many different types of database servers.
         * {} brackets allow a variable to be placed into a string
         *
         * Using a PDO and prepared statements gives some protection against SQL injection.
         */
        $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username,
                      $password, $options);
    }
    catch (PDOException $ex) {
        /*
         * If it fails to open the connection for what ever reason:
         *
         * If the application does not catch the exception thrown from the PDO constructor,
         * the default action taken by the zend engine is to terminate the script and display a back trace. #
         * This back trace will likely reveal the full database connection details, including the username and password.
         *
         * This exception needs to caught. '.' is used to concatenate strings
         */

        die("Failed to connect to the database ".$ex->getMessage());
    }
    /*Configures to PDO to use exceptions as its error reporting mode so we database errors can
        be caught in the script.*/
    $db->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);

    /*
     * Configures the PDO to return database rows using an associative array. This will use a sting as the index
     * to represent the name of the column in the database that has been returned.
     */
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    /*
     * Following code removes magic quotes if the feature is present. Removed in PHP 5.4
     * older installations may still use it though, need to disable them to prevent possible problems
     * from occurring.
     */
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {

        function undo_magic_quotes_gpc(&$array)
        {
            foreach ($array as &$value) {

                if (is_array($value)) {
                    undo_magic_quotes_gpc($value);
                } else {

                    $value = stripslashes($value);
                }
            }
        }

        undo_magic_quotes_gpc($_POST);
        undo_magic_quotes_gpc($_GET);
        undo_magic_quotes_gpc($_COOKIE);
    }

    /*This tells the web browser that your content is encoded using UTF-8
     and that it should submit content back to you using UTF-8*/
    header('Content-Type: text/html; charset=utf-8');

    session_start();

    //Database connection configuration is now complete.
?>