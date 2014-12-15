<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Item</title>

    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/jumbotron.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css">
</head>

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
                <li class="active"><a href="dashboard.html">Dashboard</a></li>
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
            <li><a href="dashboard.html">Overview</a></li>
            <li class="active"><a href="add-item.php">Add Menu Item</a></li>
            <li><a href="menu.php">View Menu</a></li>
        </ul>
    </div>

    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper" style="padding: 60px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Add Menu Item</h1>
                    <?php
                        //Checks http 'GET' to see if variables 'e' or 's' have been set.
                        if (isset($_GET['s'])) {
                            ?>
                            <!--Add a positive alert that is dismissible-->
                            <div class="alert alert-success alert-dismissable" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span
                                        aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span></button>
                                <?php echo $_GET['s']; ?>
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

                    <form class="form-horizontal" method="POST" role="form" action="scripts/add-item-script.php">
                        <div class="form-group">
                            <label for="item_name" class="col-sm-2 control-label">Item Name:</label>

                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item_name" placeholder="Item Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="item_price" class="col-sm-2 control-label">Item Price:</label>

                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item_price" placeholder="Item Price">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="item_description" class="col-sm-2 control-label">Description:</label>
                            <div class="col-md-4">
                                <textarea class="form-control" rows="5" name="item_description" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="item_category" class="col-sm-2 control-label">Category:</label>

                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item_category" placeholder="Category">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="item_stock" class="col-sm-2 control-label">Stock:</label>

                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item_stock" placeholder="Stock">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" name="submit" class="btn btn-success">Add Item</button>
                            </div>
                    </form>
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