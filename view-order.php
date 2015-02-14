<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Order</title>

    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/jumbotron.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>

<?php
    //Opens the get-menu file and reads the echoed JSON using output buffering.
    ob_start(); // begin collecting output


    //Query params passed here are passed on to the include script
    if(isset($_GET['id'])){
    $query_params[":table_number"] = $_GET['id'];
    }
    if(isset($_GET['order_id'])){
        $query_params[":order_id"] = $_GET['order_id'];
    }

    //included scripts are in the current scope, so it gets access to $query_params.
    include 'scripts/get-order-script.php';

    $json = ob_get_clean(); // retrieve output from get-menu.php, stops buffering

    //Decodes the json back into an associative array.
    $response = json_decode($json, true);

    $item = array();

    //Check to see if the table query has returned anything.

    if (isset ($response['order'])) {
        $order = $response['order'];
    }
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
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
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
            <li><a href="dashboard.php"><i class="fa fa-home" ></i> Overview</a></li>
            <li><a href="add-item.php"><i class="fa fa-plus-square-o"></i> Add Menu Item</a></li>
            <li><a href="menu.php"><i class="fa fa-eye"></i> View Menu</a></li>
            <li class="active"><a href="view-orders.php"><i class="fa fa-eye"></i> View Orders</a></li>
            <ul>
                <li class="alert-warning" > <a href="view-orders.php"> <i class="fa fa-eye"></i> View Order</a></li>
            </ul>
            <li><a href=edit-tables.php><i class="fa fa-pencil-square-o"></i> Edit Tables</a></li>
            <li><a href="edit-restaurant.php"><i class="fa fa-pencil-square-o"></i> Edit Restaurant</a></li>
        </ul>
    </div>

    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper" style="padding: 60px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Order
                        <small>#
                            <?php if (isset ($order['order_id'])) {
                                echo $order['order_id'];
                            }

                                $order_items = json_decode($order['items_info'], true);
                                $order_total = 0;
                                $paid = "NOT PAID";

                                foreach($order_items as $item){
                                    $order_total = $item['itemTotalCost'] + $order_total;
                                }

                                if($order['payment_status'] == 2){
                                    $paid = "PAID";
                                }

                            ?> </small>
                    </h1>

                    <br>
                    <h4><b>By:</b> <?php echo $order['customer_id'] ?></h4>
                    <h4><b>Total:</b> £<?php echo number_format($order_total, 2)?></h4>
                    <h4><b>On:</b> <?php echo date("l jS F - H:i", $order['order_date'] / 1000) ?></h4>
                    <h4><b><?php echo $paid ?></b></h4>
                    <br>

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $item = array();

                            //Returns the JSON string "items_info" as an associative array

                            //Iterate over every item in the item array of response
                            foreach ($order_items as $item) {
                                ?>
                                <tr>
                                    <td><?php echo $item['itemName']; ?></td>
                                    <td><?php echo $item['itemQuantity']; ?></td>
                                    <td>£<?php echo number_format($item['itemTotalCost'], 2); ?></td>
                                </tr>
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

<!-- Menu Toggle Script -->
<script>
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>
</body>