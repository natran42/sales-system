<!DOCTYPE html>
<html lang="en">

<!-- dont forget to do do form validation -->

<head>
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
