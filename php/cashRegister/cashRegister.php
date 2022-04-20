<?php include(__dir__.'/../main/nav.php');?>

<html>
    <head>
    <script>
    if(window.history.replaceState)
        window.history.replaceState(null, null, window.location.href);
    
    function confirmation() {
        window.location.href = "purchase.php?flush=true&num=" + document.getElementById('phoneNo').value;
    }
    </script>

        <link rel="stylesheet" href="cashRegister.css">
    </head>

    <title>Cash Register</title>
    <br><br>
    <div class='container'>
        <div class='row'>
            <div class='col-6'>
    <!--- Ask the user to input name and quaanitity of the item they want to buy, this input will then retrieve data from inventory-->
    <form id=entryForm action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <h2>ADD ITEM</h2>
        <div class="form-group">
            <label for="itemName">Item Name</label>
            <input type="text" class="form-control" id="itemName" name="itemName" placeholder="Enter Item Name" required>
        </div>

        <div class="form-group">
            <!-- this code will give a drop down menu of all the sizes of the item the user wants to buy -->
            <label for="itemSize">Item Size</label> 
          
            <select class="form-control" id="itemSize" name="itemSize">
                <option>N/A</option>
                <option>XS</option>
                <option>S</option>
                <option>M</option>
                <option>L</option>
                <option>XL</option>
                <option>XXL</option>
            </select>

        </div>
        <div class="form-group">
            <label for="itemQuantity">Item Quantity</label>
            <input type="text" class="form-control" id="itemQuantity" name="itemQuantity" placeholder="Enter Item Quantity" required>
        </div>
        <button type="submit" style="color:white;" class="btn btn-primary">Add To Cart</button>

    </form>
    </div>
</html>


<!-- This is the php code that will retrieve the data from inventory if there is a match, if not display that there is out of stock-->
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

    function getNextTransactionId() {
        try {
            $connection = openConnection();
            $selectQuery = 'SELECT MAX(TransactionID) AS TID FROM Transactions'; //greatest number being the last transactionID
            $getTID = sqlsrv_query($connection, $selectQuery);
            if(!$getTID)
                die(print_r(sqlsrv_errors(), true));
            $row = sqlsrv_fetch_array($getTID, SQLSRV_FETCH_ASSOC);
            return $row['TID']+1; //incrementing row by 1 -> that being our next transactionID
        }
        catch(Exception $e) {
            echo 'Error';

        }
    }
    

    function printTable(){
        //Printing header row
        echo "<div class='col-6'>
        <table border='1' class='table' style='width:100%;'>
            <tr>
                <th id=header colspan='6'>SHOPPING CART</th>
            </tr>
            <tr><td></td><td></td><td></td><td><b>Transaction #</b><b>".getNextTransactionId()."</b></td><td></td><td></td></tr>
            <tr>
                <th>Item Name</th>
                <th>Size</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Line Total</th>
                <th>Remove</th>
            </tr>";
            
        //print all rows in cart table
        $connection = openConnection();
        $total = 0;

        $query = "SELECT * FROM Cart";
        $result = sqlsrv_query($connection, $query);
        if(!$result)
            die(print_r(sqlsrv_errors(), true));

        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $upc = $row['ItemID'];
            $query = "SELECT Name, Size, Price FROM Inventory WHERE UPC = $upc";
            $result2 = sqlsrv_query($connection, $query);
            if(!$result2)
                die(print_r(sqlsrv_errors(), true));
            $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

            $itemName = isset($row2['Name']) ? $row2['Name'] : null;
            $itemSize = isset($row2['Size']) ? $row2['Size'] : null;
            $price = isset($row2['Price']) ? number_format($row2['Price'], 2) : null;

            if($itemName == null || $itemSize == null || $price == null){
                echo "x";
                continue;
            }

            echo "<tr>
            <td>$itemName</td>
            <td>$itemSize</td>
            <td>" . $row['Quantity'] . "</td>
            <td>$".number_format($price, 2)."</td>
            <td>$".number_format($price * $row['Quantity'], 2)."</td>
            <td><button class='btn btn-danger' type='button'><a class='text-light' style='color:white; text-decoration:none;' href='remove.php?deleteupc=$upc''>Remove</a></button></td>
            </tr>";

            $total += $price * $row['Quantity'];
        }
        $tax = $total * .0825;
        echo "<tr id=row><td></td><td></td><td></td><td><b>Sub Total:</b></td><td>$". number_format($total, 2)."</td><td></td></tr>";
        echo "<tr><td></td><td></td><td></td><td><b>Tax:</b></td><td>$". number_format($tax, 2)."</td><td></td></tr>";
        echo "<tr><td></td><td></td><td></td><td><b>Total:</b></td><td>$". number_format($total+$tax, 2)."</td><td></td></tr>";
        echo "<tr><td colspan='6'><button data-bs-target='#confirmCheckout' data-bs-toggle='modal' class='btn btn-primary' type='button'><a class='text-light' style='color:white; text-decoration:none;'>Purchase</a></button></td></tr></table>";
        echo "<div id=space></div>";
    }

    //retrieving form input from user
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $itemName = trim($_POST["itemName"]);
        $itemSize = trim($_POST["itemSize"]);
        $itemQuantity = trim($_POST["itemQuantity"]);
        $total = 0;

        try {
            $connection = openConnection();
            $selectQuery = "SELECT * FROM Inventory
                            WHERE Name = '$itemName' AND Size = '$itemSize' AND IsActive = 1
                            ORDER BY UPC ASC";
            $getItem = sqlsrv_query($connection, $selectQuery);
            if(!$getItem)
                echo "Item not found.";

            $row = sqlsrv_fetch_array($getItem, SQLSRV_FETCH_ASSOC); //holds all instances where query conditions are met
            if(!empty($row)) {
                $ID = $row['UPC'];
                //updating inventory once item placed into shopping cart
                if ($row['StockQty'] >= $itemQuantity) {
                    // This is the code that will update the inventory table with the new quantity
                    $updateQuery = "UPDATE Inventory SET StockQty = StockQty - $itemQuantity WHERE UPC = $ID";
                    $updateItem = sqlsrv_query($connection, $updateQuery);
                    if(!$updateItem)
                        die(print_r(sqlsrv_errors(), true));
                    
                    // Checks to see if the item already exists in the cart
                    $cartQuery = "SELECT * FROM Cart WHERE ItemID = ".$ID;
                    $checkCart = sqlsrv_query($connection, $cartQuery);
                    if(!$checkCart)
                        die(print_r(sqlsrv_errors(), true));
                    // INSERT item into Cart table  (this is the code that will insert the item into the cart table) 
                    // INSERT item if it does not already exist in the cart, otherwise UPDATE the item with new quantity
                    $cartItem = sqlsrv_fetch_array($checkCart, SQLSRV_FETCH_ASSOC);
                    $cartOperation = empty($cartItem) ? "INSERT INTO Cart (ItemID, Quantity) VALUES ('$ID', '$itemQuantity')" : "UPDATE Cart
                                                                                                                                 SET Quantity = ".$cartItem['Quantity'] + $itemQuantity."
                                                                                                                                 WHERE ItemID = ".$cartItem['ItemID'];
                    $insertItem = sqlsrv_query($connection, $cartOperation);  
                    if(!$insertItem)
                        die(print_r(sqlsrv_errors(), true));
    
                    sqlsrv_free_stmt($updateItem);
                    sqlsrv_free_stmt($checkCart);
                    sqlsrv_free_stmt($insertItem);
                }
                else {
                    echo "<script>alert('Item is out of stock.');</script>";
                }
            }
            else {
                echo "<script>alert('Invalid item');</script>";
            }
            printTable();
        }
        catch(Exception $e) {
            echo 'Error';
        }
    }
    else {
        printTable();
    }
?>

<html>
    <!-- Modal -->
    <div class="modal fade" id="confirmCheckout" tabindex="-1" aria-labelledby="confirmCheckoutLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="confirmCheckoutLabel">Check Out</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p>Enter phone number below to checkout</p>
            <form id=modalForm method="post">
                <input id="phoneNo" type="tel" name="number" onkeypress="return checkIsNum(event);" placeholder="Format: 555-555-5555" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
       required maxlength="12"><br>
            </form>
            <h6 class = "NotMember">Not a registered member yet?</h6><a href="../registration/registration.php">Click Here!</a>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="confirmation();"><a href="purchase.php?flush=true" style="color:white; text-decoration:none;">Checkout as Guest</a></button>
            <button type="button" id="memberCheckout" class="btn btn-primary" onclick="confirmation();" disabled=true style='color:white; text-decoration:none;'>Checkout</button>
        </div>
        </div>
    </div>
</html>

<script>
    let itemInput = document.querySelector('input[type=tel]') ;
    itemInput.addEventListener('keypress', phone);

    let flag = false;
    function phone() {
        let p = this.value;
        if((p.length + 1) % 4 == 0 && p.length < 9 && flag == true)
            this.value = p + "-";
        flag = true;
    }

    function checkIsNum(e) {
        var ASCIICode = (e.which) ? e.which : e.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }

    const input = document.getElementById('phoneNo');
    const button = document.getElementById('memberCheckout');

    input.addEventListener('keyup', () => {
        const enteredLength = input.value.length;
        if (enteredLength >= 12) 
            button.disabled = false;
        else
            button.disabled = true;
    });

</script>
