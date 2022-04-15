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

    echo '<table border = \'1\' class=\'table table-hover\'>
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

    
  </style>
</head>

<body>
  <div class="container mt-5 mb-5">
    <button type="button" class="btn btn-primary" id = "add" data-bs-toggle="modal" data-bs-target="#addInventory">Add Inventory</button>

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
              <label class="form-label required">Item Name</label>
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
</body>

</html>


<style>
/* style the table to match the theme */

.table{
    margin: auto;
    margin-top: 50px;
    width: auto;
    border: 2px solid #ccc;
    padding: 30px; 
    border-radius: 15px;
    color: rgb(68, 65, 65);
}

/* style the table headings */
.table th{
    border-bottom: 1px solid #ccc;
    border-right: 1px solid #ccc;
    padding: 10px;
    text-align: left;
}
/*style the table data */
.table td{
    border-bottom: 1px solid #ccc;
    border-right: 1px solid #ccc;
    padding: 10px;
    text-align: left;
}

/* place the add invetory button in the center */
.container{
    text-align: center;
}

/*style the inventroy button*/
#add{
    width: 100%;
    margin-top: 0px;
    margin-bottom: 0px;
    background-color: #0652c5;
    
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 10px;
    font-size: 16px;
    cursor: pointer;

} 
#add:after { /*after hovering*/
    content: ' » ';
    opacity: 0;
    right: -30px;
    transition: 0.5s;
    position: relative;
}

#add:hover {
    padding-right: 20px;
    background-color: #0652c5;
    background-image: linear-gradient(315deg, #0652c5 0%, #d4418e 74%);
}

#add:hover:after {
    opacity: 1;
    right: 0;
}

#add:active{ /* when clicked */
    background-color: #808080;
}




</style>