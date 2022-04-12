<?php 

if(array_key_exists('entry', $_POST)) {
    $hash = getUserCredentials($_POST['username']);
    if(!empty($hash)) {
        //Checks whether or not the login details match and allows entry to the app if matching
        if(password_verify($_POST['pwd'], $hash)) {
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


?>

<form method='post'>
    <label>Username: </label><input type='text' name='username' /><br/>
    <label>Password: </label><input type='password' name='pwd' /><br/>
    <input type='submit' name='entry' value='Login'/>
</form>

<!-- This code will style the page like the registration theme-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="php/login/login_style.css">
    <title>Login</title>
</head>


