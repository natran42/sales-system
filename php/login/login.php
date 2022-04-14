<?php

session_start();
session_unset();

if(isset($_SESSION)) {
    echo '<script>console.log(\'Hewwo\')</script>';
}

if(array_key_exists('entry', $_POST)) {
    $hash = getUserCredentials($_POST['username']);
    if(!empty($hash)) {
        //Checks whether or not the login details match and allows entry to the app if matching
        if(password_verify($_POST['pwd'], $hash)) {
            if(getUserAuthLevel($_POST['username']) === 6)
                $_SESSION['MGR'] = getUserID($_POST['username']);
            else
                $_SESSION['EMP'] = getUserID($_POST['username']);
            // DOES NOT WORK FOR LOGIN LINK ON NAVBAR
            header('location: php/cashRegister/cashregister.php');
        }    
        // else
        //     echo "Invalid login credentials";
    }
}

function openConnection() {
    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database'=>'SalesSystemDB', 'UID'=>'admin7', 'PWD'=>'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if(!$connection)
        die(print_r(sqlsrv_errors(), true));
    return $connection;
}

// Gets hashed password from the database corresponding to the username
function getUserCredentials($username) {
    try {
        $connection = openConnection();
        $sql = 'SELECT Password FROM Employees WHERE Username=?';
        $getUser = sqlsrv_prepare($connection, $sql, array(&$username));
        if(!sqlsrv_execute($getUser))
            die(print_r(sqlsrv_errors(), true));

        $result = sqlsrv_fetch_object($getUser)->Password;

        sqlsrv_free_stmt($getUser);
        sqlsrv_close($connection);

        return $result;
    }
    catch(Exception $e) {
        echo 'Error';
    }
}

// Returns authentication level of the user logging in
function getUserAuthLevel($username) {
    try {
        $connection = openConnection();
        $sql = 'SELECT Position FROM Employees WHERE Username=?';
        $getUser = sqlsrv_prepare($connection, $sql, array(&$username));
        if(!sqlsrv_execute($getUser))
            die(print_r(sqlsrv_errors(), true));

        $result = sqlsrv_fetch_object($getUser)->Position;

        sqlsrv_free_stmt($getUser);
        sqlsrv_close($connection);

        return $result;
    }
    catch(Exception $e) {
        echo 'Error';
    }
}

function getUserID($username) {
    try {
        $connection = openConnection();
        $sql = 'SELECT EID FROM Employees WHERE Username=?';
        $getUser = sqlsrv_prepare($connection, $sql, array(&$username));
        if(!sqlsrv_execute($getUser))
            die(print_r(sqlsrv_errors(), true));

        $result = sqlsrv_fetch_object($getUser)->EID;

        sqlsrv_free_stmt($getUser);
        sqlsrv_close($connection);

        return $result;
    }
    catch(Exception $e) {
        echo 'Error';
    }
}


?>

<div class='container'>
    <div class='row'>
        <div class='col-6'>
            <form method='post'>
                <label>Username: </label><input type='text' name='username' /><br/>
                <label>Password: </label><input type='password' name='pwd' /><br/>
                <input type='submit' name='entry' value='Login' />
            </form>
        </div>
        <div class='col-6'>
            <a href='php/cashRegister/cashregister.php' style="text-decoration: none";><button class = 'self'>Self-Checkout</button> </a>
        </div>
    </div>
</div>



<!-- This code will style the page like the registration theme-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="php/login/login_style.css">
    <title>Login</title>
</head>


