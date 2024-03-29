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
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>
<?php
    //Opens the get-menu file and reads the echoed JSON using output buffering.
    ob_start(); // begin collecting output

    include 'scripts/get-tables-script.php';

    $json = ob_get_clean(); // retrieve output from get-menu-script.php, stops buffering

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

            <li class="active"><a href="dashboard.php"><i class="fa fa-home"></i> Overview</a></li>
            <li><a href="add-item.php"><i class="fa fa-plus-square-o"></i> Add Menu Item</a></li>
            <li><a href="menu.php"><i class="fa fa-eye"></i> View Menu</a></li>
            <li><a href="view-orders.php"><i class="fa fa-eye"></i> View Orders</a></li>
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
                    <h1>Ordering System

                        <a href="dashboard.php" type="button" class="btn btn-default">

                            <div id="countdown">
                                <div id="minutes" style="float:left">00</div>
                                <div style="float:left">:</div>
                                <div id="seconds" style="float:left">00</div>
                            </div>
                            <div id="aftercount" style="display:none">Reload
                            </div>
                        </a>

                    </h1>

                    <BR>

                    <!-- Striped table: -->
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Table Number</th>
                            <th>Customer ID</th>
                            <th>Status</th>
                            <th>Operations</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $table = array();
                            //If the response code 'success' is 1 then there is data to work with
                            if ($response['success'] == 1) {
                                foreach ($response['tables'] as $table) {
                                    ?>
                                    <tr>
                                    <td><?php echo $table['table_number']; ?></td>
                                    <td><?php echo $table['customer_id']; ?></td>
                                    <td><?php echo $table['status']; ?></td>
                                    <td><?php

                                    if ($table['status_code'] == 0) {
                                        //Empty table, allow to be marked as in use
                                        ?>
                                        <a href="scripts/checkin-checkout.php?table_number=<?php echo $table['table_number']; ?>&method=check_in"
                                           type="button" class="btn btn-default">Set as in use</a>
                                    <?php
                                    }

                                    if ($table['status_code'] == 1 || $table['status_code'] == 5) {
                                        //Customer checked in, but no order. Or complete order
                                        ?>
                                        <a href="scripts/checkin-checkout.php?table_number=<?php echo $table['table_number']; ?>&method=check_out"
                                           type="button" class="btn btn-danger">Clear table</a>
                                    <?php
                                    }

                                    if ($table['status_code'] == 2) {
                                        //Order placed (awaiting pay)
                                        ?>
                                        <a href="view-order.php?table_number=<?php echo $table['table_number']; ?>"
                                           type="button" class="btn btn-primary"> View order</a>

                                        <a href="scripts/dispatch-order-script.php?table_number=<?php echo $table['table_number']; ?>"
                                           type="button" class="btn btn-primary">Set as dispatched</a>

                                    <?php
                                    }

                                    if ($table['status_code'] == 3 || $table['status_code'] == 6) {
                                        //Order placed (received pay)/ request csh
                                        ?>
                                        <a href="view-order.php?table_number=<?php echo $table['table_number']; ?>"
                                           type="button" class="btn btn-primary">View order</a>

                                        <a href="scripts/dispatch-order-script.php?table_number=<?php echo $table['table_number']; ?>"
                                           type="button" class="btn btn-primary">Set as dispatched</a>

                                        <a href="scripts/checkin-checkout.php?table_number=<?php echo $table['table_number']; ?>&method=check_out"
                                           type="button" class="btn btn-danger">Clear table</a>
                                    <?php
                                    }

                                    if ($table['status_code'] == 4 || $table['status_code'] == 7) {
                                        //Order dispatched (awaiting pay)
                                        ?>
                                        <a href="view-order.php?table_number=<?php echo $table['table_number']; ?>"
                                           type="button" class="btn btn-primary">View order</a>

                                        <a href="scripts/pay-order-script.php?table_number=<?php echo $table['table_number']; ?>"
                                           type="button" class="btn btn-primary">Settle bill at table</a>
                                    <?php
                                    }





                                }?>


                                </td>
                                </tr>
                            <?php
                            } //There has been an error or no data returned: output the sent message.
                            else {
                                if ($response['success'] == 0) {
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
                                                echo "Table is empty";
                                            }
                                        ?>
                                    </div>
                                <?php
                                }
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

<!--Script via, source:: http://jsfiddle.net/ysilvestrov/bUZd8/1/ -->
<script>
    var sTime = new Date().getTime();
    var countDown = 15;

    function UpdateTime() {
        var cTime = new Date().getTime();
        var diff = cTime - sTime;
        var seconds = countDown - Math.floor(diff / 1000);
        if (seconds >= 0) {
            var minutes = Math.floor(seconds / 60);
            seconds -= minutes * 60;
            $("#minutes").text(minutes < 10 ? "0" + minutes : minutes);
            $("#seconds").text(seconds < 10 ? "0" + seconds : seconds);
        } else {
            $("#countdown").hide();
            $("#aftercount").show();

            //Reloads the current page from the server and not the cache
            location.reload(true);
            clearInterval(counter);
        }
    }
    UpdateTime();
    var counter = setInterval(UpdateTime, 500);
</script>
</body>