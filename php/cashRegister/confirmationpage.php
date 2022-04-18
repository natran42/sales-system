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
        <title>Confirmation</title>
        <rel="stylesheet" href="confirmation.css">
        <meta http-equiv = "refresh" content = "3; url = cashRegister.php" />
    </head>

    <body>
        <div class="main-text">
            <h1>Thank you for your purchase!</h1>
            <p>Your transaction ID is <?php echo getNextTransactionId($connection); ?></p>
        </div>
    </body>
</html>

<style>
*{
    font-family: 'Poppins' , sans-serif;
    margin: 0;
    padding: 0;
}

/*
.main-text{
    text-align: center;
    margin-top: 10%;
    justify-content: center;
    display: flex;  
 
    align-items: center;  

}*/

body{
    justify-content: center;
    /* include backgroundLogin.jpeg as the background*/
    background-image: url("PNG image.jpeg");
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;
    background-color: #f2f2f2;
    font-family: 'Poppins' , sans-serif;
    font-size: 1.2em;
    font-weight: 300;
    color: white;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

h1{
    margin-bottom:5%;
    align-items: center;
}



p{
    font-size: 1.2em;
    font-weight: 300;
    color: white;
    text-align: center;
}

div{
    margin-top: 20%;
}

</style>