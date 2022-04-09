<?php include(__dir__ . '/../main/nav.php'); ?>



<?php

$serverName = 'sevenseas.database.windows.net';
$connectionOptions = array('Database' => 'SalesSystemDB', 'UID' => 'admin7', 'PWD' => 'TeamSeven7');
$connection = sqlsrv_connect($serverName, $connectionOptions);
if (!$connection)
    die(print_r(sqlsrv_errors(), true));


$upc = $_GET['updateupc'];

if (isset($_POST['submit'])) {
    $upc = $_GET['updateupc'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $minquantity = $_POST['minquantity'];
    $size = $_POST['size'];
    $sold = $_POST['sold'];
    $tsql = "UPDATE dbo.Inventory 
    SET StockQty = '$quantity',
    SoldQty='$sold',
    MinQty='$minquantity',
    Name='$name',
    Description='$description',
    Price='$price',
    Category='$category',
    Size='$size' 
    WHERE UPC= '$upc' ";

    $result = sqlsrv_query($connection, $tsql);
    if ($result) {
        header('location:inventory.php');
    }
}
$upc = $_GET['updateupc'];
$sql = "SELECT * FROM dbo.Inventory WHERE UPC='$upc'";
$sqlquery = sqlsrv_query($connection, $sql);
$row = sqlsrv_fetch_array($sqlquery, SQLSRV_FETCH_ASSOC);

$name1 = $row['Name'];
$description1 = $row['Description'];
$category1 = $row['Category'];
$price1 = $row['Price'];
$quantity1 = $row['StockQty'];
$minquantity1 = $row['MinQty'];
$size1 = $row['Size'];
$sold1 = $row['SoldQty'];



$sql = "SELECT * FROM dbo.Inventory WHERE UPC='$upc'";
$sqlquery = sqlsrv_query($connection, $sql);
$row = sqlsrv_fetch_array($sqlquery, SQLSRV_FETCH_ASSOC);

$name1 = $row['Name'];
$description1 = $row['Description'];
$category1 = $row['Category'];
$price1 = $row['Price'];
$quantity1 = $row['StockQty'];
$minquantity1 = $row['MinQty'];
$size1 = $row['Size'];
$sold1 = $row['SoldQty'];



?>




<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Update Inventory</title>
    <style>
        .modal-header {
            background: #F7941E;
            color: #fff;
        }

        .required:after {
            content: "*";
            color: red;

        }
    </style>
</head>

<body>
    <div class="container">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Inventory</h5>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label required">Item Name</label>
                            <input type="text" name="name" placeholder="Item Name" class="form-control" value="<?php echo $name1; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Item Description</label>
                            <input type="text" name="description" placeholder="Item Description" class="form-control" value="<?php echo $description1; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Item Category</label>
                            <!--<input type="number" name="category" placeholder="Item Category" class="form-control" value="<?php echo $category1; ?>">-->
                            <select name="category" class="form-control">
                                <option value="<?php echo $category1; ?>">--Please choose an option--</option>
                                <option value="1">Women</option>
                                <option value="2">Men</option>
                                <option value="3">Girl</option>
                                <option value="4">Boy</option>
                                <option value="5">Toddler/Baby</option>
                                <option value="6">Unisex</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Item Price</label>
                            <input type="number" step="0.01" name="price" placeholder="Item Price" class="form-control" value="<?php echo $price1; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Item Quantity</label>
                            <input type="number" name="quantity" placeholder="Item Quantity" class="form-control" value="<?php echo $quantity1; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Item Min Quantity</label>
                            <input type="number" name="minquantity" placeholder="Item Min Quantity" class="form-control" value="<?php echo $minquantity1; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Sold Quantity</label>
                            <input type="number" name="sold" placeholder="Sold Quantity" class="form-control" value="<?php echo $sold1; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Size</label>
                            <!--<input type="text" name="size" placeholder="Size" class="form-control" value="<?php echo $size1; ?>">-->
                            <select name="size" class="form-control">
                                <option value="">--Please choose an option--</option>
                                <option value="XS">XXS</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" name="submit">Update</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


</body>

</html>