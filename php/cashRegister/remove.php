<?php

    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database' => 'SalesSystemDB', 'UID' => 'admin7', 'PWD' => 'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if (!$connection)
        die(print_r(sqlsrv_errors(), true));

    if(isset($_GET['a'])){
        $delName = $_GET['a'];
        $delSize = $_GET['b'];
        $delQuantity = $_GET['c'];
        $delPrice = $_GET['d'];
    }
    $removeLine = "a=" . $delName ."&b=" . $delSize ."&c=" . $delQuantity . "&d=" . $delPrice;

    $file = fopen("cashR.txt", "r");
    while(!feof($file)){
        $line = fgets($file);
        if(strcasecmp($line, $removeLine) == 0){ //matching
        /*
            $contents = file_get_contents($file);
            $contents = str_replace($line, '', $file);
            file_put_contents($file, $contents);*/
            fwrite($file, $removeLine . "\n");
            break; //we want it to remove the first occurrence
        }
    }
    fclose($file);

    header('location:cashRegister.php');

/* REMEMBER TO UPDATE INVENTORY IF REMOVED. ADD QUANTITY BACK TO DB */
?>