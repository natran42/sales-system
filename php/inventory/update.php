<?php include(__dir__ . '/../main/nav.php'); ?>



<?php

$serverName = 'sevenseas.database.windows.net';
$connectionOptions = array('Database' => 'SalesSystemDB', 'UID' => 'admin7', 'PWD' => 'TeamSeven7');
$connection = sqlsrv_connect($serverName, $connectionOptions);

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
        //header('location:inventory.php');
        echo '<meta http-equiv = "refresh" content = "1; url = inventory.php" />';
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

?>


<!doctype html>
<html lang="en">

<head>
    <script>
    if(window.history.replaceState)
        window.history.replaceState(null, null, window.location.href);
    </script>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Update Inventory</title>
    <style>
        .modal-header {
            background: #F7941E;
            color: #fff;
        }
        /* change the text inside to black */
        .modal-content {
            color: #808080;
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
                                <option >--Please choose an option--</option>
                                <option <?php if($category1 == '1'){echo("selected");}?> value="1">Women</option>
                                <option <?php if($category1 == '2'){echo("selected");}?> value="2">Men</option>
                                <option <?php if($category1 == '3'){echo("selected");}?> value="3">Girl</option>
                                <option <?php if($category1 == '4'){echo("selected");}?> value="4">Boy</option>
                                <option <?php if($category1 == '5'){echo("selected");}?> value="5">Toddler/Baby</option>
                                <option <?php if($category1 == '6'){echo("selected");}?> value="6">Unisex</option>
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
                                <option <?php if($size1 == 'N/A'){echo("selected");}?> value="N/A">N/A</option>
                                <option <?php if($size1 == 'XS'){echo("selected");}?> value="XS">XS</option>
                                <option <?php if($size1 == 'S'){echo("selected");}?> value="S">S</option>
                                <option <?php if($size1 == 'M'){echo("selected");}?> value="M">M</option>
                                <option <?php if($size1 == 'L'){echo("selected");}?> value="L">L</option>
                                <option <?php if($size1 == 'XL'){echo("selected");}?> value="XL">XL</option>
                                <option <?php if($size1 == 'XXL'){echo("selected");}?> value="XXL">XXL</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" name="submit" onclick="window.location.replace('inventory.php');">Update</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


</body>

</html>

