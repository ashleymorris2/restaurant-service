<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Menu</title>

    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/jumbotron.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>
<?php
    //Opens the get-menu file and reads the echoed JSON using output buffering.
    ob_start(); // begin collecting output

    include 'scripts/get-menu.php';

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
            <li><a href="dashboard.php"><i class="fa fa-home"></i> Overview</a></li>
            <li><a href="add-item.php"><i class="fa fa-plus-square-o"></i> Add Menu Item</a></li>
            <li class="active"><a href="menu.php"><i class="fa fa-eye"></i> View Menu</a></li>
            <li><a href="view-orders.php"><i class="fa fa-eye"></i> View Orders</a></li>
            <li><a href=edit-tables.php><i class="fa fa-pencil-square-o"></i> Edit Tables</a></li>
            <li><a href="edit-restaurant.php"><i class="fa fa-pencil-square-o"></i> Edit Restaurant</a></li>
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Stock</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $item = array();
                            if ($response['success'] == 1) {
                                //Iterate over every item in the item array of response
                                foreach ($response['items'] as $item) {
                                    ?>
                                    <tr>
                                        <td><?php echo $item['item_id']; ?></td>
                                        <td><?php echo $item['name']; ?></td>
                                        <td>£<?php echo $item['price']; ?></td>
                                        <td><?php echo $item['description']; ?></td>
                                        <td><?php echo $item['category']; ?></td>
                                        <td><?php echo $item['stock']; ?></td>
                                        <!--Adds the item id to the URL: -->
                                        <td><a href="edit-item.php?id=<?php echo $item['item_id']; ?>" type="button"
                                               class="btn btn-default">Edit</a></td>
                                        <td>
                                            <a href="#deleteModal" data-toggle="modal"
                                               data-id="<?php echo $item['item_id']; ?>" type="button"
                                               class="open-deleteModal btn btn-danger">
                                                Delete</a>
                                        </td>
                                    </tr>
                                <?php
                                }
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
                                                echo "Failed to connect to the database";
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

<div id="deleteModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete that item?</p>

                <form class="form-group" method="POST" role="form" action="scripts/delete-item-script.php">
                    <input type="hidden" name="id" id="itemId" value=""/>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
            </div>
            </form>
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

    $(document).on("click", ".open-deleteModal", function () {

        var itemId = $(this).data('id');
        $(".modal-body #itemId").val(itemId);

    });
</script>
</body>