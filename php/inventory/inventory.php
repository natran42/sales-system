<!DOCTYPE html>
<html>
<head>
<style>
#items {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#items td, #items th {
  border: 1px solid #ddd;
  padding: 8px;
}

#items tr:nth-child(even){background-color: #f2f2f2;}

#items tr:hover {background-color: coral;}

#items th {
  padding-top: 8px;
  padding-bottom: 8px;
  text-align: left;
  background-color: crimson;
  color: white;
}
</style>
</head>
<body>
<!--examples: Delete later-->
<table id="items">
  <tr>
    <th>Item</th>
    <th>UPC </th>
    <th>Qty</th>
    <th> Cost</th>
  </tr>
  <tr>
    <td>Oranges</td>
    <td>100001</td>
    <td>2</td>
    <td> $0.6 </td>
  </tr>
  <tr>
    <td>Apples</td>
    <td>100002</td>
    <td>3</td>
    <td> $0.67</td>
  </tr>
  <tr>
    <td>PineApples</td>
    <td>100003</td>
    <td>4</td>
    <td> $0.68</td>
  </tr>
</table>

</body>
</html>




<?php
// Opens up a connection to the DB
function openConnection() {
    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database'=>'SalesSystemDB', 'UID'=>'admin7', 'PWD'=>'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if(!$connection)
        die(print_r(sqlsrv_errors(), true));
    return $connection;
}

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
    <div class="container mt-5">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInventory">Add Inventory</button>
        
    </div>
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
                                <input type="text" name="category" placeholder="Item Category" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Price</label>
                                <input type="number" step="0.01" name="price" placeholder="Item Price" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Quantity</label>
                                <input type="number" name="quantity" placeholder="Item Quantity" class="form-control">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    <!--MODAL UPDATE-->
    <div class="modal" id="updateInventory">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update</h5>
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
                                <input type="text" name="category" placeholder="Item Category" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Price</label>
                                <input type="number" step="0.01" name="price" placeholder="Item Price" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Item Quantity</label>
                                <input type="number" name="quantity" placeholder="Item Quantity" class="form-control">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</body>

</html>
