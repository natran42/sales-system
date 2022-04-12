<?php include(__dir__.'/../main/nav.php'); ?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../reporting/reportingstyle.css">
</head>

<title>Top Sellers</title>
<h3>Top 10 Sellers</h3>
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

// Selects top 10 items sold from the Inventory table
function selectTopSellers() {
    try {
        $connection = openConnection();
        $selectQuery = 'SELECT TOP 10 INV.Name, INV.Size, INV.SoldQty, INV.Price * INV.SoldQty AS GrossSales
                        FROM Inventory INV
                        ORDER BY SoldQty DESC';
        $getTopSellers = sqlsrv_query($connection, $selectQuery);
        if(!$getTopSellers)
            die(print_r(sqlsrv_errors(), true));
        
        echo "<table border = '1' class='table' >
        <tr>
        <th>Item Name</th>
        <th>Size</th>
        <th># Sold</th>
        <th>Gross Sales</th>
        </tr>";

        while($row = sqlsrv_fetch_array($getTopSellers, SQLSRV_FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>'.$row['Name'].'</td>';
            echo '<td>'.$row['Size'].'</td>';
            echo '<td>'.$row['SoldQty'].'</td>';
            echo '<td>$'.number_format($row['GrossSales'], 2).'</td>';
            echo '</tr>';
        }
    }
    catch(Exception $e) {
        echo 'Error';
    }
}

selectTopSellers();

?>

