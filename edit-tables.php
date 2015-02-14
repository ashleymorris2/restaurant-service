<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Tables</title>

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
            <li><a href="menu.php"><i class="fa fa-eye"></i> View Menu</a></li>
            <li><a href="view-orders.php"><i class="fa fa-eye"></i> View Orders</a></li>
            <li class="active"><a href=edit-tables.php><i class="fa fa-pencil-square-o"></i> Edit Tables</a></li>
            <li><a href="edit-restaurant.php"><i class="fa fa-pencil-square-o"></i> Edit Restaurant</a></li>
        </ul>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper" style="padding: 60px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9">
                    <h1>Edit Tables</h1>

                    <!-- Success and error message checking: -->
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

                    <!-- Striped table: -->
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Table Number</th>
                            <th>Operations</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $table = array();
                            //If the response code 'success' is 1 then there is data to work with
                            if ($response['success'] == 1) {
                                //Iterate over every item in the item array of response
                                foreach ($response['tables'] as $table) {
                                    ?>
                                    <tr>
                                        <td><?php echo $table['table_number']; ?></td>
                                        <!--Adds the item id to the URL: -->
                                        <td>
                                            <a href="scripts/generate_QR.php?table_number=<?php echo $table['table_number']; ?>"
                                               type="button" class="btn btn-default">Generate QR Code</a>

                                            <a href="#deleteModal" data-toggle="modal"
                                               data-id="<?php echo $table['table_number']; ?>" type="button"
                                               class=" open-deleteModal btn btn-danger">Delete
                                            </a>
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

                    <br>

                    <!--Add a new table form: -->
                    <fieldset>
                        <legend>Add Table</legend>

                        <form class="form-horizontal" method="POST" role="form" action="scripts/add-table-script.php">
                            <div class="form-group">

                                <div class="col-md-4">
                                    <input type="submit" name="submit" class="btn btn-success" value="Add"
                                           style="float: right"/>

                                    <div style="overflow: hidden; padding-right: .5em;">
                                        <input type="text" class="form-control" name="table_number"
                                               placeholder="Table Number" style="width: 100%;"/>
                                    </div>
                                    ​
                                </div>

                            </div>
                    </fieldset>
                    </form>

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
                <p>Are you sure you want to delete that table?</p>

                <form class="form-group" method="POST" role="form" action="scripts/delete-table-script.php">
                    <input type="hidden" name="table_number" id="tableNum" value=""/>

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

        var tableNum = $(this).data('id');
        $(".modal-body #tableNum").val(tableNum);

    });

</script>
</body>