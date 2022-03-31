<?php 
  /*require '../../vendor/autoload.php';

  session_start();
  if(isset($_SESSION['login']) && $_SESSION['login']) {
      //header('location: body.php');
      echo 'Logged in';
  }

  $passConfig = new PHPassLib\Application\Context;
  $passConfig->addConfig('bcrypt');

  if(array_key_exists('customerQuery', $_POST)) {
      $passHash = $passConfig->hash($_POST['pwd']);
      if($passConfig->verify($password, $hash)) {
          echo 'Success!';
      }
  }
  */
  include 'login.html'
?>


<h1>Login</h1>
<form method='post'>
    <label>Username: </label><input type='text' name='username' /><br/>
    <label>Password: </label><input type='password' name='pwd' /><br/>
    <input type='submit' name='login' value='Login'/>
</form>