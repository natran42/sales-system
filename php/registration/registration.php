<?php include(__dir__.'/../main/nav.php'); ?>

<!DOCTYPE html>
<html lang="en">

<!-- dont forget to do do form validation -->

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

    <title>Member Registration</title>
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


        <button type="submit"><span>Submit </span></button>

        </form>
</body>

</html>
