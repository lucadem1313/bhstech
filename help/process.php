<?php

    $relative = '../';
    $currentpage = 'Sign Up';
    include($relative.'includes/start.php');
    
    if(isset($_POST['checkusername']))
    {
        if(mysql_num_rows(mysql_query("SELECT * FROM users WHERE username='".$_POST['checkusername']."'")) > 0)
        {
            echo true;
        }
        else
        {
            echo false;
        }
    }

?>