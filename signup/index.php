<?php

    $relative = '../';
    $currentpage = 'Sign Up';
    include($relative.'includes/start.php');
    
    if($loggedin)
    {
        header("Location: ".$relative);
    }
    
    
    if(isset($_POST['checkusername']))
    {
        if((mysql_num_rows(mysql_query("SELECT * FROM users WHERE username='".$_POST['checkusername']."'")) > 0 || mysql_num_rows(mysql_query("SELECT * FROM unverified WHERE username='".$_POST['checkusername']."'")) > 0))
        {
            echo true;
        }
        else
        {
            echo false;
        }
    }
    
    
    $error = 0;
    if(isset($_POST['username']) && isset($_POST['password']))
    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $email = $_POST['email'];
        
        if(isset($_POST['roomnumber']))
            $roomnumber = $_POST["roomnumber"];
        else
            $roomnumber = 0;
        
        if(mysql_num_rows(mysql_query("SELECT * FROM users WHERE username='".$username."'")) < 1 && mysql_num_rows(mysql_query("SELECT * FROM unverified WHERE username='".$username."'")) < 1)
        {
            if(strlen($password) > 5)
            {
                if(!(strlen($username) > 30 || strlen($username) < 1 || strlen($firstname) > 30 || strlen($firstname) < 1 || strlen($lastname) > 30 || strlen($lastname) < 1 || strpos($email, 'bownet') === false || strpos($email, '@') === false || strpos($email, '.') === false))
                {
                    //$insertinfo = mysql_query("INSERT INTO users (firstname, lastname, username, password, mainroom, email) VALUES ('".mysql_real_escape_string($firstname)."', '".mysql_real_escape_string($lastname)."', '".mysql_real_escape_string($username)."', '".password_hash($password, PASSWORD_DEFAULT)."', '".mysql_real_escape_string($roomnumber)."', '".mysql_real_escape_string($email)."')");
                    //
                    //$_SESSION['loggedin'] = openssl_encrypt($username, ENCRYPTION_TYPE, ENCRYPTION_PASSWORD);
                    //
                    //$loggedin = true;
                    //
                    //header("Location: ".$relative);
                    
                    
                    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                    
                    $verifyid = '';
                    for ($i = 0; $i < 60; $i++) {
                         $verifyid .= $characters[rand(0, strlen($characters) - 1)];
                    }
                    
                    $table_name = "unverified"; 
                    $query = mysql_query("SHOW TABLE STATUS WHERE name='$table_name'"); 
                    $row = mysql_fetch_array($query); 
                    $nextid = $row["Auto_increment"];
                    
                    
                    $verifyid .= $nextid;
                    
                    $insertinfo = mysql_query("INSERT INTO unverified (idstring, firstname, lastname, username, password, mainroom, email) VALUES ('".mysql_real_escape_string($verifyid)."', '".mysql_real_escape_string($firstname)."', '".mysql_real_escape_string($lastname)."', '".mysql_real_escape_string($username)."', '".password_hash($password, PASSWORD_DEFAULT)."', '".mysql_real_escape_string($roomnumber)."', '".mysql_real_escape_string($email)."')");

                    
                    $to = $email;
                    $subject = "Verify BHS Tech Help Account";
                    $message = "Hello ".$firstname." ".$lastname.",\r\n Please take a moment to verify your account at the BHS Tech Help website. This is in order to assure that all accounts are real.\r\n\r\n<a href='http://lucademian.com/bhstech/verify?id=$verifyid'>Click Here</a> to verify.\r\n\r\n This is an automated email, and if this was not requested by you, please ignore this message.\r\nThank you,\r\nLuca Demian, BHS Tech Help Developer";
                    
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    
                    $headers .= 'To: '.$firstname.' '.$lastname.' <'.$to.'>' . "\r\n";
                    $headers .= "From: Luca Demian <no-reply@bhstech.bownet.org>" . "\r\n";

                    if(mail($to, $subject, $message, $headers))
                        header("Location: ".$relative."success");
                    else
                        $error = 4;
                }
                else
                {
                    $error = 3;
                }
            }
            else
            {
                $error = 2;
            }
        }
        else
        {
            $error = 1;
        }
    }
?>







<!DOCTYPE html>

<html>
<head>
    <?php include($relative."includes/head.php"); ?>
    
    <script>
        $(document).ready(function(){
            $("input[name='password']").keyup(function(){
                var string = $(this).val();
                var errorMsg = "Password must be longer than 6 characters";
                var element = $(this);
                if(string.length < 6)
                {
                    $("#error").text(errorMsg);
                    element.css('border', '2px red solid');
                }
                else
                {
                    if($("#error").text() == errorMsg)
                        $("#error").text("");
                    element.css('border', '2px lightgrey solid');
                }
            });
            $("input[name='roomnumber']").keyup(function(){
                var string = $(this).val();
                var errorMsg = "Room number is invalid";
                var element = $(this);
                if(string.length < 4 || !parseInt(string))
                {
                    $("#error").text(errorMsg);
                    element.css('border', '2px red solid');
                }
                else
                {
                    if($("#error").text() == errorMsg)
                        $("#error").text("");
                    element.css('border', '2px lightgrey solid');
                }
            });
            $("input[name='firstname']").keyup(function(){
                var string = $(this).val();
                var errorMsg = "Firstname is invalid";
                var element = $(this);
                if(string.length < 1 || string.length > 30)
                {
                    $("#error").text(errorMsg);
                    element.css('border', '2px red solid');
                }
                else
                {
                    if($("#error").text() == errorMsg)
                        $("#error").text("");
                    element.css('border', '2px lightgrey solid');
                }
            });
            $("input[name='email']").keyup(function(){
                var string = $(this).val();
                var errorMsg = "Email is invalid. Make sure it is a school email.";
                var element = $(this);
                if(string.length < 1 || string.indexOf('bownet') == -1)
                {
                    $("#error").text(errorMsg);
                    element.css('border', '2px red solid');
                }
                else
                {
                    if($("#error").text() == errorMsg)
                        $("#error").text("");
                    element.css('border', '2px lightgrey solid');
                }
            });
            $("input[name='lastname']").keyup(function(){
                var string = $(this).val();
                var errorMsg = "Lastname is invalid";
                var element = $(this);
                if(string.length < 1 || string.length > 30)
                {
                    $("#error").text(errorMsg);
                    element.css('border', '2px red solid');
                }
                else
                {
                    if($("#error").text() == errorMsg)
                        $("#error").text("");
                    element.css('border', '2px lightgrey solid');
                }
            });
            $("input[name='username']").keyup(function(){
                var string = $(this).val();
                var errorMsg = "Username is too long or short";
                var element = $(this);
                if((string.length < 1 || string.length > 30))
                {
                    $("#error").text(errorMsg);
                    element.css('border', '2px red solid');
                }
                else
                {
                    if($("#error").text() == errorMsg)
                        $("#error").text("");
                    element.css('border', '2px lightgrey solid');
                }
                
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {checkusername: string},
                    success: function(response){
                        if(response)
                        {
                            $("#error").text("Username is taken");
                            element.css('border', '2px red solid');
                        }
                        else
                        {
                            if($("#error").text() == "Username is taken")
                                $("#error").text("");
                            if(string.length > 1 && string.length < 30)
                                element.css('border', '2px lightgrey solid');
                        }
                    }
                });
            });
        });
    </script>
</head>

<body>

<?php include($relative."includes/header.php"); ?>

<div id='container'>
   
        <div class='card one'>
            <h1>Sign Up</h1>
            <form action='' method='post'>
                <input type='text' name='firstname' class='textinput' placeholder="Firstname..." value="<?php if(isset($_POST['firstname'])){echo $_POST['firstname']; }?>">
                <input type='text' name='lastname' class='textinput' placeholder="Lastname..."  value="<?php if(isset($_POST['lastname'])){echo $_POST['lastname']; }?>"><br><br>
                <input type='text' name='username' class='textinput' placeholder="Username..."  value="<?php if(isset($_POST['username'])){echo $_POST['username']; }?>">
                <input type='email' name='email' class='textinput' placeholder="Email..."  value="<?php if(isset($_POST['email'])){echo $_POST['email']; }?>"><br><br>
                <input type='password' name='password' class='textinput' placeholder="Password..."  value="<?php if(isset($_POST['password'])){echo $_POST['password']; }?>"><br><br>
                Main Room #&nbsp&nbsp&nbsp&nbsp<input type='text' size='3' name='roomnumber' class='textinput' value="<?php if(isset($_POST['roomnumber'])){echo $_POST['roomnumber']; }?>"><br><br>

                <input type='submit' value='Sign Up' class='button'>
                <span id='error'><?php
                    if($error == 1)
                        echo "Username is taken";
                    else if($error == 2)
                        echo "Password is too short";
                    else if($error == 3)
                        echo "Please fill all required fields";
                    else if($error == 4)
                        echo "Error connecting";
                ?></span>
            </form><br>
            Already have an account? <a href='../login'>Login</a>!
        </div>
    
</div>
<?php include($relative."includes/footer.php"); ?>
</body>
</html>
<?php

    mysql_close();
?>