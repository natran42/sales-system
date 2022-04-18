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

function lowStockEmpty() {
    try {
      $connection = openConnection();
      $selectQuery = 'SELECT * FROM LowStock';
      $getLowStock = sqlsrv_query($connection, $selectQuery);
      if(!$getLowStock)
          die(print_r(sqlsrv_errors(), true));

      $row = sqlsrv_fetch_array($getLowStock, SQLSRV_FETCH_ASSOC);
      return empty($row);
    }
    catch(Exception $e) {
      echo 'Error';
    }
}

function printLowStock() {
  try {
    $connection = openConnection();
    $selectQuery = 'SELECT * FROM LowStock
                    ORDER BY UPC ASC';
    $getLowStock = sqlsrv_query($connection, $selectQuery);
    if(!$getLowStock)
        die(print_r(sqlsrv_errors(), true));

    echo '<div class="modal fade" id="lowStock">
    <div class="modal-dialog" style="max-width:40%;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Low Stock</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">';

    echo '<table border = \'1\' class=\'table table-hover\' style=\'width:100%;\'>
          <tr>
          <th>UPC</th>
          <th>Name</th>
          <th>Size</th>
          <th># in Stock</th>
          </tr>';


    while($row = sqlsrv_fetch_array($getLowStock, SQLSRV_FETCH_ASSOC)) {
      echo '<tr>';
      echo '<td>'.$row['UPC'].'</td>';
      echo '<td>'.$row['Name'].'</td>';
      echo '<td>'.$row['Size'].'</td>';
      echo '<td>'.$row['StockQty'].'</td>';
      echo '</tr>';
    }
    echo '</table>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
    </div>
  </div>
</div>
  </div>';
  echo '<script>var lowStockModal = new bootstrap.Modal(document.getElementById("lowStock"));
        lowStockModal.show();</script>';
  }
  catch(Exception $e) {
    echo 'Error';
  }
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
      $tsql = 'INSERT dbo.Inventory (StockQty,SoldQty,MinQty,Name,Description,Price,Category,Size,IsActive) VALUES(?,?,?,?,?,?,?,?,?)';
      $params1 = array($quantity, $sold, $minquantity, $name, $description, $price, $category, $size, 1);
      $result = sqlsrv_query($conn, $tsql, $params1);
      if ($result) {
        //echo "Data inserted";
      } else {
        die(print_r(sqlsrv_errors(), true));
      }
    }
  } catch (Exception $e) {
    //echo ("Error!");
  }
}


function getInventory()
{
  try {
    $connection = openConnection();
    $selectQuery = 'SELECT INV.UPC, INV.Name, CTG.CtgName, INV.Size, INV.Price, INV.StockQty, INV.MinQty
                      FROM Inventory INV
                      INNER JOIN Categories CTG ON CTG.CtgID = INV.Category
                      WHERE IsActive=1
                      ORDER BY UPC ASC';
    $getItems = sqlsrv_query($connection, $selectQuery);
    if (!$getItems)
      die(print_r(sqlsrv_errors(), true));

    // Prints out headers for table
    echo "<table border = '1' class='table table-light table-hover'>
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
      $upc = $row['UPC'];
      echo $row['StockQty'] < $row['MinQty'] ? '<tr  class="table-danger">' : '<tr>';
      echo '<td>' . $itemCount++ . '</td>';
      echo '<td>' . $row['UPC'] . '</td>';
      echo '<td>' . $row['Name'] . '</td>';
      echo '<td>' . $row['CtgName'] . '</td>';
      echo '<td>' . $row['Size'] . '</td>';
      echo '<td>$' . number_format($row['Price'], 2) . '</td>';
      echo '<td>' . $row['StockQty'] . '</td>';
      echo '<td>
                <button class="btn btn-primary" type="button"><a href="update.php?updateupc=' . $upc . '" class="text-light" style="color:white; text-decoration:none;">Update</a></button>
                <button class="btn btn-danger" type="button"><a class="text-light" style="color:white; text-decoration:none;" href="delete.php?deleteupc=' . $upc . '">Delete</a></button>
                </td>';
      echo '</tr>';
    }
    echo '</table>';
    echo "<div id=space></div>";
  } catch (Exception $e) {
    echo 'Error';
  }
}


if(!lowStockEmpty()) {
  printLowStock();
}


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
   
    .table {
      margin: auto;
      width: 1000px;
      border: 2px solid #ccc;
      padding: 30px;
      background: #fff;
      border-radius: 15px;
      color: rgb(68, 65, 65);
    }
    .text-center{
      align-items: center;
      justify-content: center;
      position: relative;
      margin-bottom: 40px;
    }
    .add-btn {
      display: flex;
      position: absolute;
      height: 40px;
      padding: 0;
      background: #8e8d8a;
      border: none;
      outline: none;
      border-radius: 5px;
      overflow: hidden;
      font-family: "Quicksand", sans-serif;
      font-size: 16px;
      font-weight: 500;
      cursor: pointer;
      right: 12%;
    }

    .add-btn:hover {
      background: #008168;
    }
    .add-btn:active {
  background: #006e58;
}

.button-text,
.button-icon {
  display: inline-flex;
  align-items: center;
  padding: 0 24px;
  color: #fff;
  height: 100%;
}

.button-icon {
  font-size: 1.5em;
  background: rgba(0, 0, 0, 0.08);
}

#space{ 
    width: 100px;
    height: 70px;
}

.header{
  margin: 0;
  padding: 0;
  left: 40%;
  position: relative;
}
h1{
  font-family: 'Poppins' , sans-serif;
}
  </style>
</head>

<body>
  <div class="container mt-5 mb-5">
    <div class="row">
      <div class="header"><h1>Inventory</h1></div>
      <div class="col text-center">
      
        <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInventory">Add Inventory</button>-->
        <button type="button" data-bs-toggle="modal" data-bs-target="#addInventory" class="add-btn">
          <span class="button-text">Add Inventory</span>
          <span class="button-icon">
            <ion-icon name="add-outline"></ion-icon>
          </span>

        </button>
      </div>
    </div>


  </div>

  <?php 
    insertInventory();
    getInventory();
  ?>
  <!--MODAL ADD-->
  <div class="modal fade" id="addInventory">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Inventory</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post">
            <div class="mb-3">
              <label class="form-label required"> Item Name</label>
              <input type="text" name="name" placeholder="Item Name" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label required">Item Description</label>
              <input type="text" name="description" placeholder="Item Description" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label required">Item Category</label>
              <!--<input type="number" name="category" placeholder="Item Category" class="form-control">-->
              <select name="category" class="form-control">
                <option value="">--Please choose an option--</option>
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
              <!--<input type="text" name="size" placeholder="Size" class="form-control">-->
              <select name="size" class="form-control">
                <option value="">--Please choose an option--</option>
                <option value="N/A">N/A</option>
                <option value="XS">XS</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
              </select>

            </div>

            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
          </form>
        </div>

      </div>
    </div>
  </div>

  
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>


<!-- style Inside the pop up low stock window, have the contents fit inside the window -->
<style>
 .modal-body{
  /*max-height: calc(100vh - 210px);*/
  overflow-y: auto;
 }
 .modal-content {
            color: #808080;
  }

  /*add a title inside the inventory table*/

</style>

