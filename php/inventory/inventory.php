<?php include(__dir__ . '/../main/nav.php'); ?>



<?php
// Opens up a connection to the DB
function openConnection()
{
  $serverName = 'sevenseas.database.windows.net';
  $connectionOptions = array('Database' => 'SalesSystemDB', 'UID' => 'admin7', 'PWD' => 'TeamSeven7');
  $connection = sqlsrv_connect($serverName, $connectionOptions);
  if (!$connection)
    die(print_r(sqlsrv_errors(), true));
  return $connection;
}



function insertInventory()
{
  try {
    $conn = OpenConnection();

    if (isset($_POST['submit'])) {

      $name = $_POST['name'];
      $description = $_POST['description'];
      $category = $_POST['category'];
      $price = $_POST['price'];
      $quantity = $_POST['quantity'];
      $minquantity = $_POST['minquantity'];
      $size = $_POST['size'];
      $sold = $_POST['sold'];
      $tsql = 'INSERT dbo.Inventory (StockQty,SoldQty,MinQty,Name,Description,Price,Category,Size,IsActive) VALUES(?,?,?,?,?,?,?,?)';
      $params1 = array($quantity, $sold, $minquantity, $name, $description, $price, $category, $size, 1);
      $result = sqlsrv_query($conn, $tsql, $params1);
      if ($result) {
        //echo "Data inserted";
      } else {
        die(print_r(sqlsrv_errors(), true));
      }
    }
  } catch (Exception $e) {
    echo ("Error!");
  }
}


function getInventory()
{
  try {
    $connection = openConnection();
    $selectQuery = 'SELECT INV.UPC, INV.Name, CTG.CtgName, INV.Size, INV.Price, INV.StockQty
                      FROM Inventory INV
                      INNER JOIN Categories CTG ON CTG.CtgID = INV.Category
                      WHERE IsActive=1
                      ORDER BY UPC ASC';
    $getItems = sqlsrv_query($connection, $selectQuery);
    if (!$getItems)
      die(print_r(sqlsrv_errors(), true));

    // Prints out headers for table
    echo "<table border = '1' class='table table-success table-striped table-hover'>
      <tr>
      <th>#</th>
      <th>UPC</th>
      <th>Item Name</th>
      <th>Category</th>
      <th>Size</th>
      <th>Price</th>
      <th>Quantity in Stock</th>
      <th>Action</th>
      </tr>";
    $itemCount = 1;

    // Prints out each item as a row
    while ($row = sqlsrv_fetch_array($getItems, SQLSRV_FETCH_ASSOC)) {
      $upc=$row['UPC'];
      echo '<tr>';
      echo '<td>' . $itemCount++ . '</td>';
      echo '<td>' . $row['UPC'] . '</td>';
      echo '<td>' . $row['Name'] . '</td>';
      echo '<td>' . $row['CtgName'] . '</td>';
      echo '<td>' . $row['Size'] . '</td>';
      echo '<td>$' . number_format($row['Price'], 2) . '</td>';
      echo '<td>' . $row['StockQty'] . '</td>';
      echo '<td>
                <button class="btn btn-primary" type="button"><a href="update.php?updateupc='.$upc.'" class="text-light" ">Update</a></button>
                <button class="btn btn-danger" type="button"><a href="delete.php?deleteupc='.$upc.'">Delete</a></button>
                </td>';
      echo '</tr>';
    }
  } catch (Exception $e) {
    echo 'Error';
  }
}



insertInventory();
getInventory();


// Add something like JavaScript to make table refresh on deletion
// Something like https://stackoverflow.com/questions/15938543/php-refresh-button-onclick but try to just refresh the table

//if (isset($_GET['delete'])) {
//  $upcToDelete = validate($_GET['delete']);
//  deleteItem($upcToDelete);
//}

?>



<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

  <title>Inventory</title>
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
  <div class="container mt-5 mb-5">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInventory">Add Inventory</button>

  </div>
  <!--
  <div class="container mt-5">
    <table class="table table-success table-striped">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Description</th>
          <th scope="col">Category</th>
          <th scope="col">Price</th>
          <th scope="col">Quantity</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>Coca Cola</td>
          <td>6 packs</td>
          <td>Drinks</td>
          <td>$12.99</td>
          <td>69</td>
          <td>
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#updateInventory">Update</button>
            <button class="btn btn-danger" type="button">Delete</button>

          </td>

        </tr>
        <tr>
          <th scope="row">2</th>
          <td>Coca Cola</td>
          <td>6 packs</td>
          <td>Drinks</td>
          <td>$12.99</td>
          <td>69</td>
          <td><button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#updateInventory">Update</button>
            <button class="btn btn-danger" type="button">Delete</button>
          </td>
        </tr>
        <tr>
          <th scope="row">3</th>
          <td>Coca Cola</td>
          <td>6 packs</td>
          <td>Drinks</td>
          <td>$12.99</td>
          <td>69</td>
          <td><button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#updateInventory">Update</button>
            <button class="btn btn-danger" type="button">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  -->
  <!--MODAL ADD-->
  <div class="modal" id="addInventory">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Inventory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label required">Item Name</label>
                                <input type="text" name="name" placeholder="Item Name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Description</label>
                                <input type="text" name="description" placeholder="Item Description" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Category</label>
                                <input type="number" name="category" placeholder="Item Category" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Price</label>
                                <input type="number" step="0.01" name="price" placeholder="Item Price" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Quantity</label>
                                <input type="number" name="quantity" placeholder="Item Quantity" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Min Quantity</label>
                                <input type="number" name="minquantity" placeholder="Item Min Quantity" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Sold Quantity</label>
                                <input type="number" name="sold" placeholder="Sold Quantity" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Size</label>
                                <input type="text" name="size" placeholder="Size" class="form-control">
                            </div>
                            
                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        </form>
                    </div>
      
                </div>
            </div>
        </div>


  <!--MODAL UPDATE-->
    <div class="modal" id="updateInventory">
    <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Inventory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label required">Item Name</label>
                                <input type="text" name="name" placeholder="Item Name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Description</label>
                                <input type="text" name="description" placeholder="Item Description" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Category</label>
                                <input type="number" name="category" placeholder="Item Category" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Price</label>
                                <input type="number" step="0.01" name="price" placeholder="Item Price" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Quantity</label>
                                <input type="number" name="quantity" placeholder="Item Quantity" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Min Quantity</label>
                                <input type="number" name="minquantity" placeholder="Item Min Quantity" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Sold Quantity</label>
                                <input type="number" name="sold" placeholder="Sold Quantity" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Size</label>
                                <input type="text" name="size" placeholder="Size" class="form-control">
                            </div>
                            
                            <button type="submit" class="btn btn-primary" name="update">Update</button>
                        </form>
                    </div>
      
                </div>
            </div>
        </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</body>

</html>