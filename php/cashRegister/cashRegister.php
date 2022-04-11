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
            <label for="itemSize">Item Size</label>
            <input type="text" class="form-control" id="itemSize" name="itemSize" placeholder="Enter Item Size">
        </div>

        <div class="form-group">
            <label for="itemQuantity">Item Quantity</label>
            <input type="text" class="form-control" id="itemQuantity" name="itemQuantity" placeholder="Enter Item Quantity">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</html>


<!-- This is the php code that will retrieve the data from inventory if there is a match, if not display that there is out of stock-->
<?php

    echo 'Manager = '.$_SESSION['MGR'];
    echo '<br>Employee = '.$_SESSION['EMP'];
    echo '<br>ROOT: '.$_SERVER['DOCUMENT_ROOT'];

    // Opens up a connection to the DB
    function openConnection() {
        $serverName = 'sevenseas.database.windows.net';
        $connectionOptions = array('Database'=>'SalesSystemDB', 'UID'=>'admin7', 'PWD'=>'TeamSeven7');
        $connection = sqlsrv_connect($serverName, $connectionOptions);
        if(!$connection)
            die(print_r(sqlsrv_errors(), true));
        return $connection;
    }

    function printTable($itemName = null, $itemSize = null, $itemQuantity = null, $price = 0, &$total = 0, &$inStock = False){
        //Printing header row
        echo "<table border = '1' class='table'>
        <tr>
        <th>Item Name</th>
        <th>Size</th>
        <th>Stock Qty</th>
        <th>Price</th>
        <th>Remove</th>
        </tr>";

         //string to mkaing pulling items from file easier
         $str = "a=" . ucwords($itemName) ."&b=" . strtoupper($itemSize) ."&c=" . $itemQuantity . "&d=" . $price;

         //don't add item if not in stock
         if($inStock){
             $file = fopen("cashR.txt", "a");
             fwrite($file, $str . "\n");
             fclose($file);
         }
         else
            $inStock = True;

         //printing items to monitor
         $item_array = [];
         if ($file = fopen("cashR.txt", "a+")) {
             while(!feof($file)) {
                $line = fgets($file);
                parse_str($line, $item_array);

                //formatted this way to prevent warnings
                $a = isset($item_array['a']) ? $item_array['a'] : null;
                $b = isset($item_array['b']) ? $item_array['b'] : null;
                $c = isset($item_array['c']) ? $item_array['c'] : null;
                $d = isset($item_array['d']) ? $item_array['d'] : null;

                //we might have to change this is some items will display null
                if($a == null || $b == null || $c == null || $d == null)
                    continue;

                echo '<tr>';
                echo '<td>'. $a .'</td>';
                echo '<td>'. $b .'</td>';
                echo '<td>'. $c .'</td>';
                echo '<td>'. $d .'</td>';
                echo '<td> <button class="btn btn-primary" type="button" remove.php><a href="remove.php? '. $str .'">Remove</button></td>';
                //echo '<button class="btn btn-primary" type="button">Edit</a></button></td></td>'; //put this next to above button
                echo '</tr>';
                $total += $d;
             }
         }
    }

    // Returns next Transaction ID that should be used for the transaction
    function getNextTransactionId() {
        try {
            $connection = openConnection();
            $selectQuery = 'SELECT MAX(TransactionID) AS TID FROM Transactions';
            $getTID = sqlsrv_query($connection, $selectQuery);
            if(!$getTID)
                die(print_r(sqlsrv_errors(), true));
    
            $row = sqlsrv_fetch_array($getTransactions, SQLSRV_FETCH_ASSOC);
            return $row['TID']+1;
        }
        catch(Exception $e) {
            echo 'Error';
        }
    }

    //printTable();

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
</style>
