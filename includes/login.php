<?php

    if(isset($_COOKIE["loggedin"]))
    {
        if($_COOKIE['loggedin'] != (null || ''))
        {
            $username = openssl_decrypt($_COOKIE["loggedin"], ENCRYPTION_TYPE, ENCRYPTION_PASSWORD);
            $loggedin = true;
            
            $selectuserinfo = mysql_query("SELECT * FROM users WHERE username='$username'");
            while($row = mysql_fetch_array($selectuserinfo))
            {
                $userid = $row{"id"};
            }
            $selectmod = mysql_query("SELECT * FROM moderators WHERE userid=$userid");
            $godmode = false;
            if(mysql_num_rows($selectmod) > 0)
            {
                $mod = true;
                while($row = mysql_fetch_array($selectmod))
                {
                    if($row{'godmode'} == 1)
                        $godmode = true;
                }
            }
            else
            {
                $mod = false;
            }
        }
    }
    else if(isset($_SESSION["loggedin"]))
    {
        $username = openssl_decrypt($_SESSION["loggedin"], ENCRYPTION_TYPE, ENCRYPTION_PASSWORD);

        $loggedin = true;
        
        $selectuserinfo = mysql_query("SELECT * FROM users WHERE username='$username'");
        while($row = mysql_fetch_array($selectuserinfo))
        {
            $userid = $row{"id"};
        }
        $selectmod = mysql_query("SELECT * FROM moderators WHERE userid=$userid");
        $godmode = false;
        if(mysql_num_rows($selectmod) > 0)
        {
            $mod = true;
            while($row = mysql_fetch_array($selectmod))
            {
                if($row{'godmode'} == 1)
                    $godmode = true;
            }
        }
        else
        {
            $mod = false;
        }
    }
    else
    {
        $loggedin = false;
        $username = null;
    }
    
    
?>