<?php

    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database' => 'SalesSystemDB', 'UID' => 'admin7', 'PWD' => 'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if (!$connection)
        die(print_r(sqlsrv_errors(), true));

    // if the delete button is clicked we will delete the whole car
    
    if(isset($_GET['flush']))
        $sqlquery = "DELETE FROM Cart";


    $result = sqlsrv_query($connection, $sqlquery);
    if($result){
        header('location:cashRegister.php');
    }
    else
        die(print_r(sqlsrv_errors(), true));

?>
