<?php

    $relative = '../';
    $currentpage = 'Login';
    include($relative.'includes/start.php');
    
    if($loggedin)
    {
        header("Location: ".$relative);
    }
    
    $error = 0;
    $trylogin = false;
    if(isset($_POST['password-login']) && isset($_POST['username-login']))
    {
        $trylogin = true;
        $loggedin = false;
        $username = $_POST["username-login"];
        $password = $_POST["password-login"];
        $remember = $_POST["remember"];
        
        $query = mysql_query("SELECT * FROM users WHERE username='$username'");
        
        if(mysql_num_rows($query) > 0)
        {
            while($row = mysql_fetch_array($query))
            {
                if($row{"username"} == $username && password_verify($password, $row{'password'}))
                {
                    if($remember == "true")
                        setcookie("loggedin", openssl_encrypt($username, ENCRYPTION_TYPE, ENCRYPTION_PASSWORD), time()+60*60*24*30, "/");

                    else
                    {
                        $_SESSION['loggedin'] = openssl_encrypt($username, ENCRYPTION_TYPE, ENCRYPTION_PASSWORD);
                    }
                    
                    $loggedin = true;
                    
                    header("Location: ".$relative);
                }
                else{
                    $error = 1;
                }
            }
        }
        else
        {
            $loggedin = false;
            $error = 1;
        }
    }
?>







<!DOCTYPE html>

<html>
<head>
    <?php include($relative."includes/head.php"); ?>
</head>

<body>

<?php include($relative."includes/header.php"); ?>

<div id='container'>
   
        <div class='card one'>
            <h1>Login</h1>
            <form action='' method='post'>
                <input type='text' name='username-login' class='textinput' placeholder="Username..."><br><br>
                <input type='password' name='password-login' class='textinput' placeholder="Password..."><br><br>
                <input type='hidden' name='remember' value='false'>
                <input type='checkbox' name='remember' value='true'> Remember Me?<br><br>
                Please contact the tech. dept. if you forget your password.<br><br>
                <input type='submit' value='Login' class='button'>
                <span id='error'><?php
                    if($error == 1)
                        echo "Username and password don't match!";
                ?></span>
            </form><br>
            Don't have an account? <a href='../signup'>Sign Up</a>!
        </div>
    
</div>
<?php include($relative."includes/footer.php"); ?>
</body>
</html>
<?php

    mysql_close();
?>
