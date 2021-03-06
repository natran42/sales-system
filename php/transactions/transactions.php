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

//->Input customer transaction id
//->Pull up previous transaction info from transaction id


function fetchTransactions($userTransaction) {
    try {
        $connection = openConnection();
        $selectQuery = "SELECT DISTINCT TRA.TransactionID, CUS.FirstName + ' ' + CUS.LastName AS OrderedBy, TRA.TypeOfTransaction, TRA.TransactionDate, EMP.FirstName + ' ' + EMP.LastName AS ProcessedBy, TOT.SubTotal, TOT.Tax, TOT.Total
                        FROM Transactions TRA
                        INNER JOIN Customers CUS ON TRA.OrderedBy = CUS.UUID
                        INNER JOIN Employees EMP ON TRA.ProcessedBy = EMP.EID
                        INNER JOIN TransactionItems TRI ON TRA.TransactionID = TRI.TransactionID
                        INNER JOIN Inventory INV ON TRI.TransactionItemID = INV.UPC
                        INNER JOIN (SELECT TTRA.TransactionID, FORMAT(SUM(TTRI.Quantity * TINV.Price), 'N2') AS SubTotal, FORMAT(SUM(TTRI.Quantity * TINV.Price) * 0.0825, 'N2') AS Tax, FORMAT(SUM(TTRI.Quantity * TINV.Price) * 1.0825, 'N2') AS Total
                                    FROM Transactions TTRA
                                    INNER JOIN TransactionItems TTRI ON TTRA.TransactionID = TTRI.TransactionID
                                    INNER JOIN Inventory TINV ON TTRI.TransactionItemID = TINV.UPC
                                    GROUP BY TTRA.TransactionID
                                    ) TOT ON TRA.TransactionID = TOT.TransactionID
                        WHERE TRA.TransactionID = '$userTransaction'";
        $getTransactions = sqlsrv_query($connection, $selectQuery);
        if(!$getTransactions)
            die(print_r(sqlsrv_errors(), true));

        $selectSecondQuery =   "SELECT TRI.TransactionItemID, INV.Name, INV.Size, TRI.Quantity, FORMAT(TRI.Quantity * INV.Price, 'N2') AS SumPrice
                                FROM TransactionItems TRI
                                INNER JOIN Inventory INV ON TRI.TransactionItemID = INV.UPC
                                WHERE TransactionID = '$userTransaction'";
        $getTransactionItems = sqlsrv_query($connection, $selectSecondQuery);
        if(!$getTransactionItems)
            die(print-r(sqlsrv_errors(), true));

        $row = sqlsrv_fetch_array($getTransactions, SQLSRV_FETCH_ASSOC);
        if(empty($row)) {
            echo "<script>alert('Transaction does not exist');</script>";
        }
        else {
            echo "<h3></h3>
            <table border = '1' class='table table-hover'>
            <tr>
            <th>Transaction ID</th>
            <th>Ordered By</th>
            <th>Type of Transaction</th>
            <th>Transaction Date</th>
            <th>Processed By</th>
            <th>Subtotal</th>
            <th>Tax</th>
            <th>Total</th>
            </tr>";   
    
            echo '<tr>';
            echo '<td>'.$row['TransactionID'].'</td>';
            echo '<td>'.$row['OrderedBy'].'</td>';
            echo '<td>'.$row['TypeOfTransaction'].'</td>';
            echo '<td>'.$row['TransactionDate']->format('Y-m-d').'</td>';
            echo '<td>'.$row['ProcessedBy'].'</td>';
            echo '<td>$'.$row['SubTotal'].'</td>';
            echo '<td>$'.$row['Tax'].'</td>';
            echo '<td>$'.$row['Total'].'</td>';
            echo '</tr>';
           
            echo "<table border = '1' class='table table-hover'>
            <tr>
            <th>Item ID</th>
            <th>Name</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Item Total</th>
            </tr>";   
    
            echo "<h3></h3>";
            echo "<h3>Items Purchased:</h3>";
    
            while($row = sqlsrv_fetch_array($getTransactionItems, SQLSRV_FETCH_ASSOC)) {
            
                echo '<tr>';
                echo '<td>'.$row['TransactionItemID'].'</td>';
                echo '<td>'.$row['Name'].'</td>';
                echo '<td>'.$row['Size'].'</td>';
                echo '<td>'.$row['Quantity'].'</td>';
                echo '<td>$'.$row['SumPrice'].'</td>';
                echo '</tr>';
            }
    
            sqlsrv_free_stmt($getTransactions);
            sqlsrv_free_stmt($getTransactionItems);
            sqlsrv_close($connection);
        }

    }
    catch(Exception $e) {
        echo 'Error';
    }
}
//Should we use UUID or Phone #?
?>

<header>
    <link rel="stylesheet" href="transactions.css">
</header>

<title>Transactions</title>
<form action = "transactions.php" method='post'>
    <h1>Customer Transactions</h1>
    Enter Transaction ID: <input type="number" name="transID"/> <br/>
    <input type='submit' name='submit' id='submit' value='Submit'/>
</form>

<?php

if(!empty($_POST['submit'])){
    $userTransaction = $_POST['transID'];
    fetchTransactions(intval($userTransaction));
}

?>


