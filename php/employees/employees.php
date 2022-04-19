<?php include(__dir__.'/../main/nav.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../employees/employeesstyle.css">
</head>


<title>Employees</title>
    <form method='post'>
        <h3>Current Employees</h3>
    <select name='employee' class="form-select" aria-label="Default select example">
        <option value="" disabled selected>Choose option</option>
        <option value='7'>All Employees</option>
        <option value='6'>General Managers</option>
        <option value='5'>Assistant Managers</option>
        <option value='1'>Cashiers</option>
        <option value='2'>Stocker</option>
        <option value='4'>Floor Recoverers</option>
        <option value='3'>Custodians</option>
    </select>

    <input type='submit' name='submit' value='View Results'>
    <button type="button" data-bs-toggle="modal" data-bs-target="#addEmployee" class="add-btn">
          <span class="button-text">Add Employee</span>
          <span class="button-icon">
            <ion-icon name="add-outline"></ion-icon>
          </span>
    </button>
</form>

<?php

function openConnection() {
    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database'=>'SalesSystemDB', 'UID'=>'admin7', 'PWD'=>'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if(!$connection)
        die(print_r(sqlsrv_errors(), true));
    return $connection;
}

function selectEmployees($selected) {
    try {
        $connection = openConnection();
        switch($selected){
            case '1';
            $val = "Cashiers";
            break;

            case '2';
            $val = 'Stockers';
            break;

            case '3';
            $val = 'Custodians';
            break;

            case '4';
            $val = 'Floor Recoverers';
            break;

            case '5';
            $val = 'Assistant Managers';
            break;

            case '6';
            $val = 'General Managers';
            break;

            case '7';
            $val = 'All Employees';
            break;

            default:
                break;
        }
        if ($selected == '7'){
            $selectQuery = "SELECT EID, FirstName, LastName, PhoneNumber, Email, IsActive, Username FROM Employees";
        }
        else{
            $selectQuery = "SELECT EID, FirstName, LastName, PhoneNumber, Email, IsActive, Username FROM Employees WHERE Position = $selected";
        }
        
        $getEmployees = sqlsrv_query($connection, $selectQuery);
        if(!$getEmployees)
            die(print_r(sqlsrv_errors(), true));
        echo "<br> <br>";
        echo "<table border = '1' class='table table-hover'>
        <tr>
        <th id=header colspan='5'> $val</th>
        </tr>
        <tr>
        <th>#</th>
        <th>EID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Phone Number</th>
        <th>Email</th>
        <th>Is Active</th>
        <th>Username</th>
        </tr>";
        $count = 1;

        while($row = sqlsrv_fetch_array($getEmployees, SQLSRV_FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>'.$count++.'</td>';
            echo '<td>'.$row['EID'].'</td>';
            echo '<td>'.$row['FirstName'].'</td>';
            echo '<td>'.$row['LastName'].'</td>';
            echo '<td>'.$row['PhoneNumber'].'</td>';
            echo '<td>'.$row['Email'].'</td>';
            echo '<td>'.$row['IsActive'].'</td>';
            echo '<td>'.$row['Username'].'</td>';
            echo '</tr>';
        
        }
        
    }
    catch(Exception $e) {
        echo 'Error';
    }
}

function addNewEmployee(){
    try {
        $conn = OpenConnection();
    
          $firstName = $_POST['first'];
          $lastName = $_POST['last'];
          $number = $_POST['number'];
          $email = $_POST['email'];
          $position = $_POST['position'];
          $startDate = $_POST['startDate'];
          $username = $_POST['username'];
          $password = $_POST['password'];
          $tsql = 'INSERT dbo.Employees (FirstName,LastName,PhoneNumber,Email,Position,Start_Dt,End_Dt,IsActive,Username, Password) VALUES(?,?,?,?,?,?,?,?,?,?)';
          $params1 = array($firstName, $lastName, $number, $email, $position, $startDate, NULL, 1, $username, $password);
          $result = sqlsrv_query($conn, $tsql, $params1);
          if ($result) {
            echo "Data inserted";
          } else {
            die(print_r(sqlsrv_errors(), true));
          }
        } catch (Exception $e) {
      }
    }


if(isset($_POST['submit'])){
if(!empty($_POST['employee'])) {
    $selected = $_POST['employee'];
    selectEmployees($selected);

    } else {
        addNewEmployee();
        echo 'Please select the value.';
    }
    }




?>
<body>
        

  </div>
<div class="modal fade" id="addEmployee">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post">
            <div class="mb-3">
              <label class="form-label required"> First Name</label>
              <input type="text" name="first" placeholder="First Name" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label required">Last Name</label>
              <input type="text" name="last" placeholder="Last Name" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label required">Phone Number</label>
              <input type="text" name="number" placeholder="Format: 555-555-5555" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label required">Email</label>
              <input type="text" name="email" placeholder="Email" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label required">Position</label>
              <select name="Position" class="form-control">
                <option value="">--Select an option--</option>
                <option value="1">Cashier</option>
                <option value="2">Stocker</option>
                <option value="3">Custodian</option>
                <option value="4">Floor Recoverer</option>
                <option value="5">Assistant Manager</option>
                <option value="6">General Manager</option>
              </select>

            </div>
            <div class="mb-3">
              <label class="form-label required">Start Date</label>
              <input type="date" name="startDate" placeholder="Start Date" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label required">Username</label>
              <input type="text" name="username" placeholder="Username" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label required">Password</label>
              <input type="text" name="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
          </form>
        </div>

      </div>
    </div>
  </div>

  
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>









  

