<?php

    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database' => 'SalesSystemDB', 'UID' => 'admin7', 'PWD' => 'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if (!$connection)
        die(print_r(sqlsrv_errors(), true));

    //If the purchase button is clicked we want to get the data from the cart and insert it into the transactions table
    if (isset($_POST['purchase'])) {
        $transactionId = getNextTransactionId();
        $query = "INSERT INTO Transactions VALUES ('$transactionId', '$_SESSION[EMP]', '$_SESSION[MGR]', '$_SESSION[CUST]', '$_SESSION[TOTAL]', '$_SESSION[DATE]')";
        $result = sqlsrv_query($connection, $query);
        if (!$result)
            die(print_r(sqlsrv_errors(), true));

        //Inserting the data from the cart into the transactions table
        $query = "SELECT * FROM Cart";
        $result = sqlsrv_query($connection, $query);
        if (!$result)
            die(print_r(sqlsrv_errors(), true));

        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $query = "INSERT INTO TransactionItems VALUES ('$transactionId', '$row[ITEM_NAME]', '$row[ITEM_SIZE]', '$row[ITEM_QUANTITY]', '$row[ITEM_PRICE]')";
            $result = sqlsrv_query($connection, $query);
            if (!$result)
                die(print_r(sqlsrv_errors(), true));
        }

        //Updating the inventory table
        $query = "SELECT * FROM Cart";
        $result = sqlsrv_query($connection, $query);
        if (!$result)
            die(print_r(sqlsrv_errors(), true));

        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $query = "UPDATE Inventory SET QUANTITY = QUANTITY - '$row[ITEM_QUANTITY]' WHERE ITEM_NAME = '$row[ITEM_NAME]' AND ITEM_SIZE = '$row[ITEM_SIZE]'";
            $result = sqlsrv_;

        }
    }


    if(isset($_GET['flush']))
        $sqlquery = "DELETE FROM Cart";

    $result = sqlsrv_query($connection, $sqlquery);
    if($result)
        header('location:cashRegister.php');
    else
        die(print_r(sqlsrv_errors(), true));

?>
