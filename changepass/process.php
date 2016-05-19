<?php

    $relative = '../';
    $currentpage = 'Sign Up';
    include($relative.'includes/start.php');
    
    if(isset($_POST['newpass']))
    {
        $findoldpass = mysql_query("SELECT * FROM users WHERE id=$userid");
        while($row = mysql_fetch_array($findoldpass))
        {
            if($_POST['newpass'] == $_POST['confirmpass'])
            {
                if(password_verify($_POST['originalpass'], $row{'password'}))
                {
                    mysql_query("UPDATE users SET password='".password_hash($_POST['newpass'], PASSWORD_DEFAULT)."' WHERE id=$userid");
                }
                else
                    echo "error2";
                    
            }
            else
            {
                echo "error1";
            }
        }
    }
    else
    {
        echo "error3";
    }

?>