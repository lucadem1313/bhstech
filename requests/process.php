
<?php

    $relative = '../';
    $currentpage = 'Sign Up';
    include($relative.'includes/start.php');
    
    if(isset($_POST['approve']))
    {
        if($mod)
        {
            mysql_query("UPDATE tickets SET approved=1 WHERE id=".$_POST['approve']);


        }
    }
    if(isset($_POST['finish']))
    {
        if($mod)
        {
            mysql_query("UPDATE tickets SET dismissed=1 WHERE id=".$_POST['finish']);

            mysql_query("INSERT INTO ticketaction(ticketid, userid) VALUES ('".mysql_real_escape_string($_POST['finish'])."','".mysql_real_escape_string($userid)."')");
            
            mysql_query("INSERT INTO dismissal (ticketid, userid, description, difficulty, duration) VALUES ('".mysql_real_escape_string($_POST['finish'])."','".mysql_real_escape_string($userid)."','".mysql_real_escape_string($_POST['text'])."','".mysql_real_escape_string($_POST['difficulty'])."','".mysql_real_escape_string($_POST['duration'])."')");
            
            mysql_query("UPDATE users SET karma=karma+15 WHERE id=".$userid);
            
        }
    }
    if(isset($_POST['delete']))
    {
        if($mod)
            mysql_query("DELETE FROM tickets WHERE id=".$_POST['delete']);
    }
    if(isset($_POST['message']))
    {

        $table_name = "messages"; 
        $query = mysql_query("SHOW TABLE STATUS WHERE name='$table_name'"); 
        $row = mysql_fetch_array($query); 
        $nextid = $row["Auto_increment"];

        if($mod)
        {

            mysql_query("INSERT INTO ticketreplies (ticketid, userid, messageid) VALUES ('".mysql_real_escape_string($_POST['ticketid'])."','".mysql_real_escape_string($userid)."','".mysql_real_escape_string($nextid)."')");

            mysql_query("INSERT INTO messages (touser, fromuser, message) VALUES ('".mysql_real_escape_string($_POST['to'])."','".mysql_real_escape_string($userid)."','".mysql_real_escape_string($_POST['text'])."')");

        }
    }

    mysql_close();
?>