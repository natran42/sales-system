<?php

function openConnection() {
    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database'=>'SalesSystemDB', 'UID'=>'admin7', 'PWD'=>'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if(!$connection)
        die(print_r(sqlsrv_errors(), true));
    return $connection;
}

function getNextTransactionId($connection) {
    try {
        $selectQuery = 'SELECT MAX(TransactionID) AS TID FROM Transactions'; //greatest number being the last transactionID
        $getTID = sqlsrv_query($connection, $selectQuery);
        //$getTransactions = sqlsrv_fetch_array($getTID, SQLSRV_FETCH_ASSOC);
        if(!$getTID)
            die(print_r(sqlsrv_errors(), true));
        $row = sqlsrv_fetch_array($getTID, SQLSRV_FETCH_ASSOC);
        return $row['TID']+1; //incrementing row by 1 -> that being our next transactionID
    }
    catch(Exception $e) {
        echo 'Error';
    }
}

$connection = openConnection();

?>

<html>
    <head>
        <meta http-equiv = "refresh" content = "3; url = cashRegister.php" />
    </head>

    <body>
        <h1>Thank you for your purchase!</h1>
        <p>Your transaction ID is <?php echo getNextTransactionId($connection); ?></p>
    </body>
</html>