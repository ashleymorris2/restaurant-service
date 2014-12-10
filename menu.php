<head>
    <h1>View Menu</h1>
</head>
<?php
    /**
     * Created by PhpStorm.
     * User: Ashley Morris
     * Date: 04/12/2014
     * Time: 00:00
     *
        menu.php will get a list of all the items that are currently in the database.
        Will create a separate script that will sync the mySql database with an android
        SQLlite one so that the user only has to download the menu data once.
        This second script will send JSON data.

     */

    //connect to the database
    require("config.inc.php");

    //the initial query (select all from menu order by category)
    $query = "SELECT *FROM menu ORDER BY category, price ASC";

    //Execute the query
    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute();
    }
    catch (PDOException $ex) {
        $response['success'] = 0;
        $response['message'] = "Error connecting to the database";
        json_encode($response);
    }

    //Use fetch all to retrieve all the found rows into an array
    $rows = $stmt->fetchAll();


    //If the query has returned any results then we will put it into an array and parse into JSON and out put via html
    if ($rows) {
        $response['success'] = 1;
        $response['message'] = "Connected successfully";

        //says that the key 'items' is an array.
        $response['items'] = array();
        ?>

        <table>
            <?php
                /*
                 * For every loop iteration, the value of the current array element ($rows) is
                 * assigned to $row and the array pointer is moved by one,
                 * until it reaches the last array element.
                 */
                foreach ($rows as $row) {
                    $item = array();

                    $item['item_id'] = $row['item_id'];
                    $item['name'] = $row['name'];
                    $item['price'] = $row['price'];
                    $item['description'] = $row['description'];
                    $item['category'] = $row['category'];
                    $item['stock'] = $row['stock'];
                    ?>

                    <tr>
                        <td> <?php echo $item['item_id']; ?></td>
                        <td> <?php echo $item['name']; ?></td>
                        <td> £<?php echo $item['price']; ?></td>
                        <td> <?php echo $item['description']; ?></td>
                        <td> <?php echo $item['category']; ?></td>
                    </tr>

                    <?php
                    //update our response with json data, pushes this post onto the posts array.
                    array_push($response['items'], $item);
                }
            ?>
        </table>
        <?php
        echo(json_encode($response['items']));
    }
    else {
        echo "No data was found";
    }

?>