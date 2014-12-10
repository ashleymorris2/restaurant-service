<?php
    /**
     * Created by PhpStorm.
     * User: Ashley Morris
     * Date: 06/12/2014
     * Time: 23:18
     */

        //This function is a simple redirect function that takes one parameter (The Page that is to be redirected to)
    function redirct_to($new_location)
    {
        header("Location: " . $new_location);
        exit;
    }

?>