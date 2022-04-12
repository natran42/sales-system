<?php

session_start();
session_unset();

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
            exit();
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
                <input type='submit' name='entry' value='Login'/>
            </form>
        </div>
        <div class='col-6'>
            <a href='php/cashRegister/cashregister.php'><button>Self-Checkout</button></a>
        </div>
    </div>
</div>

<!-- This code will style the page like the registration theme-->
<head>
<style>    
*{
    font-family: sans-serif;
    box-sizing: border-box;
}

body {
    background: #1690A7;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

h2 {
    text-align: center;
    margin-bottom: 40px;
}

form {
    width: 500px;
    border: 2px solid #ccc;
    padding: 30px;
    background: #fff;
    border-radius: 15px;
}

input {
    display: block;
    border: 2px solid;
    width: 100%;
    padding: 10px;
    margin: 10px auto;
    border-radius: 5px;
}

.inline{
    display: inline-block;
    width: 49%;
}

#right{
    float: right;
}

label{
    color: #888;
    font-size: 18px;
    padding: 10px;
}

button{
    background: #d6d6d6;
    padding: 15px 15px;
    color: black;
    border-radius: 5px;
    text-align: center;
    width: 100%;
    font-size: 16px;
    cursor: pointer;
    border-width: 1px;
}

button{
  cursor: pointer;
  display: inline-block;
  position: relative;
  transition: 0.5s;
}

button:after {
  content: '»';
  opacity: 0;
  top: 10;
  right: -50px;
  transition: 0.5s;

  position: relative;
}

button:hover {
  padding-right: 35px;
}

button:hover:after {
  opacity: 1;
  right: 0;
}

button:active{
   background-color: #808080;
}
</style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="php/registration/registration_style.css">

    <title>Login</title>
</head>
