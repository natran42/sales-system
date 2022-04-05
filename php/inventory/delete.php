<?php 

    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database' => 'SalesSystemDB', 'UID' => 'admin7', 'PWD' => 'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if (!$connection)
        die(print_r(sqlsrv_errors(), true));



    if(isset($_GET['deleteupc'])){
        $upc=$_GET['deleteupc'];
    
        $sqlquery = "UPDATE Inventory
                    SET IsActive = 0
                    WHERE UPC =$upc";
        
        $result = sqlsrv_query($connection, $sqlquery);
        if($result){
            header('location:inventory.php');
        }else{
            die(print_r(sqlsrv_errors(), true));
        }
    }

?>
