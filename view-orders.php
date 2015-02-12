<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/jumbotron.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css">
</head>
<?php
    //Opens the get-menu file and reads the echoed JSON using output buffering.
    ob_start(); // begin collecting output

    include 'scripts/get-orders-script.php';

    $json = ob_get_clean(); // retrieve output from get-menu.php, stops buffering

    //Decodes the json back into an associative array.
    $response = json_decode($json, true);
?>
<body>
<!-- navigation bar set up -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid ">
        <div class="navbar-header">
            <a href="#" class="navbar-brand">Go Eat</a>

            <div id="buttons">
                <a href="#menu-toggle" role="button" class="btn btn-default navbar-btn" id="menu-toggle">
                    <span class="glyphicon glyphicon-align-justify"></span>
                </a>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.htm">Home</a></li>
                <li class="active"><a href="dashboard.php">Dashboard</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- /#wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand">
                <a href="#">Admin Panel</a>
            </li>
            <li><a href="dashboard.php">Overview</a></li>
            <li><a href="add-item.php">Add Menu Item</a></li>
            <li><a href="menu.php">View Menu</a></li>
            <li class="active"><a href="view-orders.php">View Orders</a></li>
            <li><a href="edit-tables.php">Edit Tables</a></li>
            <li><a href="edit-restaurant.php">Edit Restaurant</a></li>
        </ul>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper" style="padding: 60px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1>View Menu</h1>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Payment Status</th>
                            <th>Table Number</th>
                            <th>Date</th>
                            <th>Operations</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $order = array();
                            if ($response['success'] == 1) {

                                $paid = "NOT PAID";

                                //Iterate over every item in the item array of response
                                foreach ($response['orders'] as $order) {
                                   if($order['payment_status'] == 2){
                                       $paid = "PAID";
                                   }
                                    ?>
                                    <tr>
                                        <td><?php echo $order['order_id']; ?></td>
                                        <td><?php echo $order['customer_id']; ?></td>
                                        <td>£<?php echo number_format($order['order_total'], 2); ?></td>
                                        <td><?php echo $paid ?></td>
                                        <td><?php echo $order['table_number']; ?></td>
                                        <td><?php echo date("(D) - d/M/Y - H:i", $order['order_date'] / 1000) ?></td>

                                        <td><a href="view-order.php?order_id=<?php echo $order['order_id']; ?>" type="button"
                                               class="btn btn-default">View</a></td>
                                        <td>

                                        </td>
                                    </tr>
                                <?php
                                }
                            } //There has been an error or no data returned: output the sent message.
                            else if ($response['success'] == 0)  {
                                ?>
                                <div class="alert alert-danger alert-dismissable" role="alert">
                                    <button type="button" class="close" data-dismiss="alert"><span
                                            aria-hidden="true">&times;</span>
                                        <span class="sr-only">Close</span></button><?php
                                        /*Outputs the error message that was sent
                                        with the response.*/
                                        echo $response['message'];

                                        if (empty ($response['message'])) {
                                            //Database connection has failed;
                                            echo "Failed to connect to the database";
                                        }
                                    ?>
                                </div>
                            <?php
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!--Footer stuff, jquery import. Speeds page loading if at the bottom.-->
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="js/bootstrap.js"></script>

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="http://cdn.datatables.net/1.10.4/js/jquery.dataTables.js"></script>


<!-- Menu Toggle Script -->
<script>
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>
