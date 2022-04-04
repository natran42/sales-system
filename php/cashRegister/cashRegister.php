<?php include(__dir__.'/../main/nav.php'); ?>


<?php
    function openConnection() {
        $serverName = 'sevenseas.database.windows.net';
        $connectionOptions = array('Database'=>'SalesSystemDB', 'UID'=>'admin7', 'PWD'=>'TeamSeven7');
        $connection = sqlsrv_connect($serverName, $connectionOptions);
        if(!$connection)
            die(print_r(sqlsrv_errors(), true));
        return $connection;
    }
    function selectInventory($inputName) {
        /* 
        1. get item from input
        2. get the price using item input from Inventory table
        3. update the quantatiy of the item in the inventory
        */
        echo "wokrs";

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
    try {
        $connection = openConnection();
        $selectQuery = "SELECT * FROM Inventory WHERE Name = '$itemName' AND Size = '$itemSize'";
        $getItem = sqlsrv_query($connection, $selectQuery);
        if(!$getItem)
            die(print_r(sqlsrv_errors(), true));
        $row = sqlsrv_fetch_array($getItem, SQLSRV_FETCH_ASSOC);
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
        while($row = sqlsrv_fetch_array($getItem, SQLSRV_FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>'.$row['Name'].'</td>';
            echo '<td>'.$row['Size'].'</td>';
            echo '<td>'.$row['StockQty'].'</td>';
            echo '<td>$'.number_format($row['Price'], 2).'</td>';
            echo '</tr>';
        }

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

