<?php include 'cash.html'?>
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
            $selectQuery = 'SELECT PRICE, Name FROM Inventory WHERE Name == \''.$inputName.'\'';
            $getItems = sqlsrv_query($connection, $selectQuery);
            if(!$getItems)
                die(print_r(sqlsrv_errors(), true));
    
            echo "<table border = '1'>
            <tr>
            <th>Item Name</th>
            <th>Price Name</th>
            </tr>";
            //$total = 0;      
            while($row = sqlsrv_fetch_array($getItems, SQLSRV_FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>'.$row['Item Name'].'</td>';
                echo '<td>'.$row['Price Name'].'</td>';
                echo '</tr>';
                // $total = $total + PRICE; adds up all the prices 
            }
    
            sqlsrv_free_stmt($getItems);
            sqlsrv_close($connection);
        }
        catch(Exception $e) {
            echo 'Error';
        }
    }
?>



<?php
    if(isset($_POST['submit'])){
        $userItem = $_POST['item'];
        selectInventory($userItem);
    }
?>