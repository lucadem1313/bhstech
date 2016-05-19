<?php

    $relative = '../';
    $currentpage = 'Sign Up';
    include($relative.'includes/start.php');
    
    if(isset($_POST['checkusername']))
    {
        if((mysql_num_rows(mysql_query("SELECT * FROM users WHERE username='".$_POST['checkusername']."'")) > 1 || mysql_num_rows(mysql_query("SELECT * FROM unverified WHERE username='".$_POST['checkusername']."'")) > 0))
        {
            echo true;
        }
        else
        {
            echo false;
        }
    }
    
    if(isset($_POST['username']))
    {
        $username = $_POST["username"];
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        
        if(isset($_POST['roomnumber']))
            $roomnumber = $_POST["roomnumber"];
        else
            $roomnumber = 0;
        
        if(mysql_num_rows(mysql_query("SELECT * FROM users WHERE username='".$username."'")) < 2 && mysql_num_rows(mysql_query("SELECT * FROM unverified WHERE username='".$username."'")) < 1)
        {
            if(true)
            {
                if(!(strlen($username) > 30 || strlen($username) < 1 || strlen($firstname) > 30 || strlen($firstname) < 1 || strlen($lastname) > 30 || strlen($lastname) < 1))
                {
                    $insertinfo = mysql_query("UPDATE users SET firstname='".$firstname."', lastname='".$lastname."', username='".$username."', mainroom='".$roomnumber."' WHERE id=$userid");
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