<?php

    $relative = '../';
    $currentpage = 'Sign Up';
    include($relative.'includes/start.php');
    

    if(isset($_POST['delete']))
    {
        
        $selectmod = mysql_query('SELECT * FROM moderators WHERE userid='.$_POST['delete']);
        if(mysql_num_rows($selectmod) < 1)
        {
            $ismod = false;
        }
        else
        {
            $ismod = true;
        }
                
                
        if($mod && !$ismod)
            mysql_query("DELETE FROM users WHERE id=".$_POST['delete']);
            
        if($godmode && $ismod)
            mysql_query("DELETE FROM users WHERE id=".$_POST['delete']);
    }
    if(isset($_POST['message']))
    {
        if($mod)
            mysql_query("INSERT INTO messages (touser, fromuser, message) VALUES ('".mysql_real_escape_string($_POST['to'])."','".mysql_real_escape_string($userid)."','".mysql_real_escape_string($_POST['text'])."')");
    }
    if(isset($_POST['upgrade']))
    {
        if($mod)
            mysql_query("INSERT INTO moderators (userid) VALUES ('".mysql_real_escape_string($_POST['upgrade'])."')");
    }
    if(isset($_POST['downgrade']))
    {
        if($godmode)
            mysql_query("DELETE FROM moderators WHERE userid='".$_POST['downgrade']."'");
    }

    mysql_close();
?>

