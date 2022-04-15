<?php

    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database' => 'SalesSystemDB', 'UID' => 'admin7', 'PWD' => 'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if (!$connection)
        die(print_r(sqlsrv_errors(), true));

    //delete row from cart table    
    if(isset($_GET['deleteupc'])){
        $upc = $_GET['deleteupc'];
        $selectCartQuery = "SELECT * FROM Cart WHERE ItemID = $upc";
        $selectCart = sqlsrv_query($connection, $selectCartQuery);
        $cartItem = sqlsrv_fetch_array($selectCart, SQLSRV_FETCH_ASSOC);
        $cartQuantity = $cartItem['Quantity'];
        $sqlquery = "DELETE FROM Cart WHERE ItemID = $upc";
        $result = sqlsrv_query($connection, $sqlquery);
        $updateQuery = "UPDATE Inventory SET StockQty = StockQty + $cartQuantity WHERE UPC = $upc";
        $updateStock = sqlsrv_query($connection, $updateQuery);
        if($result)
           header('location:cashRegister.php');
        else
            die(print_r(sqlsrv_errors(), true));
        sqlsrv_free_stmt($selectCart);
        sqlsrv_free_stmt($result);
    }
?>