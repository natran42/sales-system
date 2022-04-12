<?php

    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database' => 'SalesSystemDB', 'UID' => 'admin7', 'PWD' => 'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if (!$connection)
        die(print_r(sqlsrv_errors(), true));

    //delete row from cart table    
    if(isset($_GET['deleteupc'])){
        $upc = $_GET['deleteupc'];
        $sqlquery = "DELETE FROM Cart WHERE ItemID = $upc";
        $result = sqlsrv_query($connection, $sqlquery);
        if($result)
           header('location:cashRegister.php');
        else
            die(print_r(sqlsrv_errors(), true));
    }
        
/* REMEMBER TO UPDATE INVENTORY IF REMOVED. ADD QUANTITY BACK TO DB */

    $sql = "DELETE FROM Inventory WHERE ItemName = '$delName' AND Size = '$delSize'";
    $stmt = sqlsrv_query($connection, $sql);
    if($stmt === false)
        die(print_r(sqlsrv_errors(), true));
    sqlsrv_free_stmt($stmt);
    $sql = "UPDATE Inventory SET Quantity = Quantity + $delQuantity WHERE ItemName = '$delName' AND Size = '$delSize'";

    
?>