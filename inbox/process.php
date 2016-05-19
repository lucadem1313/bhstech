<?php

    $relative = '../';
    $currentpage = 'Sign Up';
    include($relative.'includes/start.php');
    

    if(isset($_POST['delete']))
    {
        if($mod)
            mysql_query("UPDATE messages SET deleted=1 WHERE id=".$_POST['delete']);
    }
    if(isset($_POST['message']))
    {
            mysql_query("INSERT INTO messages (touser, fromuser, message) VALUES ('".mysql_real_escape_string($_POST['to'])."','".mysql_real_escape_string($userid)."','".mysql_real_escape_string($_POST['text'])."')");
    }

    mysql_close();
?>