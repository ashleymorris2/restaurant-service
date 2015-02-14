<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Restaurant</title>

    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/jumbotron.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>

<?php
    //Opens the get-menu file and reads the echoed JSON using output buffering.
    ob_start(); // begin collecting output


    //included scripts are in the current scope, so it gets access to $query_params.
    include 'scripts/get-restaurant-details.php';

    $json = ob_get_clean(); // retrieve output from get-menu.php, stops buffering

    //Decodes the json back into an associative array.
    $response = json_decode($json, true);

    $restaurant = array();

    //Check to see if the query has returned anything.
    if (isset ($response['restaurant'])) {
        $restaurant = $response['restaurant'];
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
            <li><a href="dashboard.php"><i class="fa fa-home" ></i> Overview</a></li>
            <li><a href="add-item.php"><i class="fa fa-plus-square-o"></i> Add Menu Item</a></li>
            <li><a href="menu.php"><i class="fa fa-eye"></i> View Menu</a></li>
            <li><a href="view-orders.php"><i class="fa fa-eye"></i> View Orders</a></li>
            <li><a href=edit-tables.php><i class="fa fa-pencil-square-o"></i> Edit Tables</a></li>
            <li class="active"><a href="edit-restaurant.php"><i class="fa fa-pencil-square-o"></i> Edit Restaurant</a></li>
        </ul>
    </div>

    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper" style="padding: 60px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Edit Restaurant </h1>
                    <?php
                        //Checks http 'GET' to see if variables 'e' or 's' have been set.
                        //S is success, e is error.
                        if (isset($_GET['s'])) {
                            ?>
                            <!--Add a positive alert that is dismissible-->
                            <div class="alert alert-success alert-dismissable" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span
                                        aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span></button>
                                <strong>Success: </strong><?php echo $_GET['s']; ?>
                            </div>
                        <?php
                        }

                        else {
                            if (isset($_GET['e'])) {
                                ?>
                                <div class="alert alert-danger alert-dismissable" role="alert">
                                    <button type="button" class="close" data-dismiss="alert"><span
                                            aria-hidden="true">&times;</span>
                                        <span class="sr-only">Close</span></button>
                                    <strong>Error: </strong><?php echo $_GET['e']; ?>
                                </div>
                            <?php
                            }
                        }
                    ?>

                    <br>

                    <fieldset>
                        <legend>Restaurant Details</legend>
                        <form class="form-horizontal" method="POST" role="form" action="scripts/edit-restaurant-script.php">
                            <div class="form-group">
                                <label for="item_name" class="col-sm-2 control-label">Name:</label>

                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="name"
                                           value="<?php echo $restaurant['name'] ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="item_description" class="col-sm-2 control-label">Address:</label>

                                <div class="col-md-4">
                                    <textarea class="form-control" rows="5"
                                              name="address"><?php echo $restaurant['address'] ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="item_price" class="col-sm-2 control-label">Phone Number:</label>

                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="phone_number"
                                           value="<?php echo $restaurant['phone_number'] ?> ">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="item_category" class="col-sm-2 control-label">Open Time:</label>

                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="open_time"
                                           value="<?php echo $restaurant['open_time'] ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="item_stock" class="col-sm-2 control-label">Close Time:</label>

                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="close_time"
                                           value="<?php echo $restaurant['close_time']; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" name="submit" class="btn btn-success">Submit</button>
                                </div>
                        </form>
                    </fieldset>
                    <br>

                    <fieldset>
                        <legend>Restaurant Image</legend
                        <div class="col-sm-6">
                            
                            <img title="Current image" style="float: left; margin-right: 20px"  src="<?php echo $restaurant['image']; ?>" class="img-thumbnail" width="300" height="300">

                            <form action="img-upload.php" method="post" enctype="multipart/form-data">
                                Select an image that represents your restaurant:
                                <br>

                                <div class="input-group col-sm-4">
                                    <span class="input-group-btn">>
                                <span class="btn btn-default btn-file">
                                Browse... <input type="file" name="fileToUpload" id="" class="file">
                                </span>
                                        </span>
                                    <input type="text" class="form-control" readonly>
                                    <input type="hidden" name="restaurant_id" value="<?php echo $restaurant['restaurant_id']; ?>">
                                </div>
                                <br>
                                <input type="submit" value="Submit" name="submit" class="btn btn-success">
                            </form>
                        </div>
                    </fieldset>
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


    //Script via: abeautifulsite.net/whipping-file-inputs-into-shape-with-bootstrap-3/
    $(document).on('change', '.btn-file :file', function () {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready(function () {
        $('.btn-file :file').on('fileselect', function (event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

            if (input.length) {
                input.val(log);
            } else {
                if (log) alert(log);
            }

        });
    });
</script>


</body>