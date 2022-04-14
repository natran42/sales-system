<?php include(__dir__.'/../main/nav.php');?>

<html>
    <head>
        <link rel="stylesheet" href="cashRegister.css">
    </head>

    <title>Cash Register</title>

    <!--- Ask the user to input name and quaanitity of the item they want to buy, this input will then retrieve data from inventory-->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

        <div class="form-group">
            <label for="itemName">Item Name</label>
            <input type="text" class="form-control" id="itemName" name="itemName" placeholder="Enter Item Name">
        </div>

        <div class="form-group">
                  <!-- this code will give a drop down menu of all the sizes of the item the user wants to buy -->
            <label for="itemSize">Item Size</label>
            <select class="form-control" id="itemSize" name="itemSize">
                <option>N/A</option>
                <option>XS</option>
                <option>S</option>
                <option>M</option>
                <option>L</option>
                <option>XL</option>
                <option>XXL</option>
            </select>
        </div>
        <div class="form-group">
            <label for="itemQuantity">Item Quantity</label>
            <input type="text" class="form-control" id="itemQuantity" name="itemQuantity" placeholder="Enter Item Quantity">

        </div>

        <button type="submit" class="btn btn-primary">Add To Cart</button>

    </form>
</html>

<!-- have the submit color as #8ee8d8a0 -->
<style>

    .btn-primary {
        background-color: #8e8d8a;
        border-color: #8e8d8a;
        color: black;
    }

</style>

<!-- This is the php code that will retrieve the data from inventory if there is a match, if not display that there is out of stock-->
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

    function getNextTransactionId() {
        try {
            $connection = openConnection();
            $selectQuery = 'SELECT MAX(TransactionID) AS TID FROM Transactions'; //greatest number being the last transactionID
            $getTID = sqlsrv_query($connection, $selectQuery);
            if(!$getTID)
                die(print_r(sqlsrv_errors(), true));
    
            $row = sqlsrv_fetch_array($getTransactions, SQLSRV_FETCH_ASSOC);
            return $row['TID']+1; //incrementing row by 1 -> that being our next transactionID
        }
        catch(Exception $e) {
            echo 'Error';

        }
    }

    

    function printTable($itemName = null, $itemSize = null, $itemQuantity = null, $price = 0, &$total = 0, &$inStock = False){
        //Printing header row
        echo "<table border = '1' class='table'>
        <tr>
        <th>Item Name</th>
        <th>Size</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Remove</th>
        </tr>";
            
        //print all rows in cart table
        $query = "SELECT * FROM Cart";
        $result = sqlsrv_query($connection, $query);
        if(!$result)
            die(print_r(sqlsrv_errors(), true));

        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $upc = $row['ItemID'];
            $query = "SELECT Name, Size, Price FROM Inventory WHERE UPC = $upc";
            $result2 = sqlsrv_query($connection, $query);
            if(!$result2)
                die(print_r(sqlsrv_errors(), true));
            $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

            $itemName = isset($row2['Name']) ? $row2['Name'] : null;
            $itemSize = isset($row2['Size']) ? $row2['Size'] : null;
            $price = isset($row2['Price']) ? number_format($row2['Price'], 2) : null;

            if($itemName == null || $itemSize == null || $price == null){
                echo "x";
                continue;
            }

            echo "<tr>
            <td>$itemName</td>
            <td>$itemSize</td>
            <td>" . $row['Quantity'] . "</td>
            <td>$price</td>
            <td><a href='remove.php?deleteupc=$upc'>Remove</a></td>
            </tr>";

            $total += $price;
        }

        echo "<br>Total: $". number_format($total, 2);
    }

    //retrieving form input from user
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $itemName = $_POST["itemName"];
        $itemSize = $_POST["itemSize"];
        $itemQuantity = $_POST["itemQuantity"];
        $total = 0;

        try {
            $connection = openConnection();
            $selectQuery = "SELECT * FROM Inventory WHERE Name = '$itemName' AND Size = '$itemSize'";
            $getItem = sqlsrv_query($connection, $selectQuery);
            if(!$getItem)
                echo "Item not found.";

            $row = sqlsrv_fetch_array($getItem, SQLSRV_FETCH_ASSOC); //holds all instances where query conditions are met
            $ID = $row['UPC'];
            //updating inventory once item placed into shopping cart
            if ($row['StockQty'] >= $itemQuantity) {
                // This is the code that will update the inventory table with the new quantity
                $updateQuery = "UPDATE Inventory SET StockQty = StockQty - $itemQuantity WHERE Name = '$itemName' AND Size = '$itemSize'";
                $updateItem = sqlsrv_query($connection, $updateQuery);
                if(!$updateItem)
                    die(print_r(sqlsrv_errors(), true));

                // This code will update the inventory table with the SoldQty
                $updateQuery = "UPDATE Inventory SET SoldQty = SoldQty + $itemQuantity WHERE Name = '$itemName' AND Size = '$itemSize'";
                $updateItem = sqlsrv_query($connection, $updateQuery);
                if(!$updateItem)
                    die(print_r(sqlsrv_errors(), true));
                    
                //INSERT item into Cart table  (this is the code that will insert the item into the cart table) 
                $insertQuery = "INSERT INTO Cart (ItemID, Quantity) VALUES ('$ID', '$itemQuantity')"; 
                $insertItem = sqlsrv_query($connection, $insertQuery);  
                if(!$insertItem)
                    die(print_r(sqlsrv_errors(), true));
            }
            else {
                echo "Item is out of stock";
            }
            printTable($connection, $itemName, $itemSize, $itemQuantity, number_format($row['Price'], 2), $total);

        }
        catch(Exception $e) {
            echo 'Error';
        }
    }
    /* we could use action= to direct user to webpage confirming purchase after submitting */

?>

<html>
    <button class="btn btn-primary" type="button"><a class="text-light" style="color:white; text-decoration:none;" href="purchase.php?flush=true">Purchase</a></button>
</html>

<!-- style this page to make it look like a cash register -->
<style>
    .form-group {
        display: inline-block;
        margin-right: 10px;
    }
    
    .table{
    margin: auto;
    width: auto;
    border: 2px solid #ccc;
    padding: 30px; 
    background: #fff;
    border-radius: 15px;
    color: rgb(68, 65, 65);
}
</style>
