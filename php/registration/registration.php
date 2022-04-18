<?php include(__dir__.'/../main/nav.php'); ?>

<!DOCTYPE html>
<html lang="en">

<!-- dont forget to do form validation -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../registration/registration_style.css">
    <title>Member Registration</title>
    <script>
    if(window.history.replaceState)
        window.history.replaceState(null, null, window.location.href);
    </script>
</head>

<body>
    <form method="post">
        <h2>MEMBER REGISTRATION</h2>
        <label>Full Name</label><br>
        <input type="text" name="first" placeholder="First Name" class="inline">
        <input type="text" name="last" placeholder="Last Name" class="inline" id="right">
        <br><br>

        <label>Phone Number</label>
        <input type="tel" name="number" placeholder="Format: 555-555-5555" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
       required maxlength="12"><br>

        <script>

            let itemInput = document.querySelector('input[type=tel]') ;
            itemInput.addEventListener('keypress', phone);

            let flag = false;
            function phone(){
                let p = this.value;
                if((p.length + 1) % 4 == 0 && p.length < 9 && flag == true)
                    this.value = p + "-";
                flag = true;
            }

        </script>

        <!-- <span id="helpBlock" class="help-block" >Format: 123-456-7890.</span><br><br> we can probably add this for if the user inputs wrong information to tell them how to format a correct input -->

        <label>Email</label>
        <input type="email" step="0.01" name="email" placeholder="Email"><br>

        <label>Address</label>
        <input type="text" name="address" placeholder="Street Name">
        <input type="text" name="city" placeholder="City" class="inline">
        <input type="text" name="state" placeholder="State / Province" class="inline" id="right">
        <input type="text" name="zip" placeholder="Zip Code / Postal" class="inline">
        <br>

        <button type="submit"><span>Submit</span></button>
    </form>

    <div id=space>
    </div>

</body>
</html>

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

?>

<!--- this code will be used to add user to the customer table from user input-->
<?php
if(isset($_POST['first'])) {
    $first = $_POST['first'];
    $last = $_POST['last'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];

    $connection = openConnection();

    $sql = "INSERT INTO Customers (FirstName, LastName, PhoneNumber, Email, Address, AddressState, AddressZip, AddressCity, Start_DtTm, IsActive) VALUES ('$first', '$last', '$number', '$email', '$address', '$state', '$zip', '$city', GETDATE(), 1)";
    $result = sqlsrv_query($connection, $sql);
    if($result === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    // else {
    //     echo "Successfully added customer";
    // }
}

