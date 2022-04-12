<?php include(__dir__.'/../main/nav.php');?>

<html>
<title>Register</title>

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

        <button type="submit" class="btn btn-primary">Add</button>
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

        //Printing data rows
        if($itemName != null && $itemSize != null && $itemQuantity != null){
            echo "<tr>";
            echo "<td>" . $itemName . "</td>";
            echo "<td>" . $itemSize . "</td>";
            echo "<td>" . $itemQuantity . "</td>";
            echo "<td>" . $price . "</td>";
            echo "<td><a href='remove.php?itemName=$itemName&itemSize=$itemSize&itemQuantity=$itemQuantity'>Remove</a></td>";
            echo "</tr>";
            
            $inStock = True;
        }
    }

   // printTable();

    //retrieving form input from user
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $itemName = $_POST["itemName"];
        $itemSize = $_POST["itemSize"];
        $itemQuantity = $_POST["itemQuantity"];
        $inStock = True;
        $total = 0;

        try {
            $connection = openConnection();
            $selectQuery = "SELECT * FROM Inventory WHERE Name = '$itemName' AND Size = '$itemSize'";
            $getItem = sqlsrv_query($connection, $selectQuery);
            if(!$getItem)
                die(print_r(sqlsrv_errors(), true));
            $row = sqlsrv_fetch_array($getItem, SQLSRV_FETCH_ASSOC); //holds all instances where query conditions are met
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

                $total = $itemQuantity * $row['Price'];
                
            }
            else {
                echo "Item is out of stock";
                $inStock = False;
            }
            printTable($itemName, $itemSize, $itemQuantity, number_format($row['Price'], 2), $total, $inStock);

        }
        catch(Exception $e) {
            echo 'Error';
        }
        echo "<br>Total: $". number_format($total, 2);

    }
    /* we could use action= to direct user to webpage confirming purchase after submitting */

?>

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
