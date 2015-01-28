<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Item</title>

    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/jumbotron.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css">
</head>

<?php
    //Opens the get-menu file and reads the echoed JSON using output buffering.
    ob_start(); // begin collecting output

    $query_params[":id"] = $_GET['id'];

    //included scripts are in the current scope, so it gets access to $query_params.
    include 'scripts/get-item-script.php';

    $json = ob_get_clean(); // retrieve output from get-menu.php, stops buffering

    //Decodes the json back into an associative array.
    $response = json_decode($json, true);

    $item = array();

    //Check to see if the table query has returned anything.

    if (isset ($response['item'])) {
        $item = $response['item'];
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
            <li><a href="dashboard.php">Overview</a></li>
            <li><a href="add-item.php">Add Menu Item</a></li>
            <li><a href="menu.php">View Menu</a></li>
            <li><a href="edit-tables.php">Edit Tables</a></li>
        </ul>
    </div>

    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper" style="padding: 60px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Edit Menu Item
                        <small>ID:
                            <?php if (isset ($item['item_id'])) {
                                echo $item['item_id'];
                            }
                            ?> </small>
                    </h1>
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
                                <?php echo $_GET['s']; ?><br> <a href="menu.php" class="alert-link">Click here to return
                                    to the menu.</a>
                            </div>
                        <?php
                        } else {
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

                    <?php if (isset ($item['item_id'])) {
                        /*Checks if the item_id has been set, if not then the item hasn't been found so don't display
                        the table */
                        ?>
                        <form class="form-horizontal" method="POST" role="form" action="scripts/edit-item-script.php">
                            <div class="form-group">
                                <label for="item_name" class="col-sm-2 control-label">Item Name:</label>

                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="item_name"
                                           value="<?php echo $item['name'] ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="item_price" class="col-sm-2 control-label">Item Price:</label>

                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="item_price"
                                           value="<?php echo $item['price'] ?> ">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="item_description" class="col-sm-2 control-label">Description:</label>

                                <div class="col-md-4">
                                    <textarea class="form-control" rows="5"
                                              name="item_description"><?php echo $item['description'] ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="item_category" class="col-sm-2 control-label">Category:</label>

                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="item_category"
                                           value="<?php echo $item['category'] ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="item_stock" class="col-sm-2 control-label">Stock:</label>

                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="item_stock"
                                           value="<?php echo $item['stock']; ?>">
                                </div>
                            </div>

                            <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" name="submit" class="btn btn-success">Done</button>
                                    <a href="menu.php" class="btn btn-danger">Discard</a>
                                </div>
                        </form>
                    <?php
                    } else {
                        if (!isset($_GET['s']) || !isset($_GET['e']))
                        //This means that we haven't returned to this page from the edit-item-script.
                        {
                            ?>
                            <div class="alert alert-danger" role="alert">The item wasn't found.</div> <?php
                        }
                    } ?>
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