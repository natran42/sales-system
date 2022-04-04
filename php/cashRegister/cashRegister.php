<?php include(__dir__.'/../main/nav.php'); ?>


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





<!-- This is the php code that will retrieve the data from inventory if there is a match, if not display that there is out of stock-->
<?php

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
            die(print_r(sqlsrv_errors(), true));
        $row = sqlsrv_fetch_array($getItem, SQLSRV_FETCH_ASSOC);
        if(is_null($row['StockQty']) == 1){
            die(print_r(sqlsrv_errors(), true));}
        if ($row['StockQty'] >= $itemQuantity) {
            echo "Item is in stock <br>";
            // This is the code that will update the inventory table with the new quantity
            $updateQuery = "UPDATE Inventory SET StockQty = StockQty - $itemQuantity WHERE Name = '$itemName' AND Size = '$itemSize'";
            $updateItem = sqlsrv_query($connection, $updateQuery);
            if(!$updateItem)
                die(print_r(sqlsrv_errors(), true));
            // This code will update the invetory table with the SoldQty
            $updateQuery = "UPDATE Inventory SET SoldQty = SoldQty + $itemQuantity WHERE Name = '$itemName' AND Size = '$itemSize'";
            $updateItem = sqlsrv_query($connection, $updateQuery);
            if(!$updateItem)
                die(print_r(sqlsrv_errors(), true));



            //this is the code that will update total, this is the total amount of money that the user will have to pay based on the quantity * price
            $total = $itemQuantity * $row['Price'];
        }
        else {
            echo "Item is out of stock";
        }

        //this code will display the updated inventory table
        $selectQuery = "SELECT * FROM Inventory";
        $getItem = sqlsrv_query($connection, $selectQuery);
        if(!$getItem)
            die(print_r(sqlsrv_errors(), true));
        echo "<table border = '1' class='table'>
        <tr>
        <th>Item Name</th>
        <th>Size</th>
        <th>Stock Qty</th>
        <th>Price</th>
        </tr>";


        $str = "a=" . $itemName ."&b=" . $itemSize ."&c=" . $itemQuantity . "&d=" . number_format($row['Price'], 2);
        $file = fopen("cashR.txt", "w+");
        fwrite($file, "" . $str . "\n");
        fclose($file);


        $item_array = [];
        if ($file = fopen("cashR.txt", "r+")) {
            while(!feof($file)) {
                $line = fgets($file);
                parse_str($line, $item_array);
                echo '<tr>';
                echo '<td>'.$item_array['a'].'</td>';
                echo '<td>'.$item_array['b'].'</td>';
                echo '<td>'.$item_array['c'].'</td>';
                echo '<td>$'.$item_array['d'].'</td>';
                echo '</tr>';
                fwrite($file, "\n");
            }
            fclose($file);
        }
/*
         $new_array = array($itemName, $itemSize, $itemQuantity, number_format($row['Price'], 2));
         $item_array[] = $new_array;
         echo "count: " . count($item_array) . '\n';
         for($i = 0; $i < count($item_array); $i++){
             for($j = 0; $j < 4; $j++){
             echo "here:";
             echo '\n';
                 echo $item_array[$i][$j];
             }
         }*/
    }
    catch(Exception $e) {
        echo 'Error';
    }

    //this code will display the total amount of money that the user will have to pay
    echo "<br>Total: $".number_format($total, 2);
}


?>


<!-- style this page to make it look like a cash register -->
<style>
    .form-group {
        display: inline-block;
        margin-right: 10px;
    }
</style>

