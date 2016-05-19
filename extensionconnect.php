<?php
    header("Access-Control-Allow-Origin: chrome-extension://kdljodbjhihdcbcogjfgbjflnfcbhnad");
    
    
    $relative = '';
    $currentpage = 'Home';
    include($relative.'includes/start.php');
    

    
    if(isset($_POST['title']) && isset($_POST['description']))
    {
        $title = str_split($_POST["title"], 40)[0];
        $topic = $_POST["topic"];
        $description = $_POST["description"];
        $roomnumber = $_POST["roomnumber"];
        
        if($_POST['depend'] == 'false')
            $depend = 0;
        else
            $depend = 1;
        
        
        if($_POST['inclass'] == 'false')
            $inclass = 0;
        else
            $inclass = 1;
        


        $table_name = "tickets"; 
        $query = mysql_query("SHOW TABLE STATUS WHERE name='$table_name'"); 
        $row = mysql_fetch_array($query); 
        $nextid = $row["Auto_increment"];

        $equation = ((100 - ($inclass*20)) - ($depend * 30)) - ($nextid/10);
        
        if(true)
        {
                if((strlen($title) > 0 && strlen($topic) > 0 && strlen($description) > 0 && strlen($roomnumber) > 0))
                {
                    $insertinfo = mysql_query("INSERT INTO tickets (title, description, topic, roomnumber, userid, inclass, classdependant, relevance) VALUES ('".mysql_real_escape_string($title)."', '".mysql_real_escape_string($description)."', '".mysql_real_escape_string($topic)."', '".mysql_real_escape_string($roomnumber)."', '".mysql_real_escape_string($userid)."', '".mysql_real_escape_string($inclass)."', '".mysql_real_escape_string($depend)."', '".mysql_real_escape_string($equation)."')") or die(mysql_error());
                    
                    unset($_POST['title']);
                    unset($_POST['topic']);
                    unset($_POST['description']);
                    unset($_POST['roomnumber']);
                    unset($_POST['depend']);
                    unset($_POST['inclass']);
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
    
    
    if(isset($_POST['password-loginext']) && isset($_POST['username-loginext']))
    {
        $trylogin = true;
        $loggedin = false;
        $username = $_POST["username-loginext"];
        $password = $_POST["password-loginext"];
        
        $query = mysql_query("SELECT * FROM users WHERE username='$username'");
        
        if(mysql_num_rows($query) > 0)
        {
            while($row = mysql_fetch_array($query))
            {
                if($row{"username"} == $username && password_verify($password, $row{'password'}))
                {
                    setcookie("loggedinextension", openssl_encrypt($username, ENCRYPTION_TYPE, ENCRYPTION_PASSWORD), time()+60*60*24*360, "/");
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
        
    if(isset($_POST['logout-ext']))
    {
        if(isset($_COOKIE['loggedinextension']))
        {
            unset($_COOKIE['loggedinextension']);
            setcookie('loggedinextension', null, -1, '/');
        }
    }
    
    if(isset($_POST['checkextlogin']))
    {
        if(isset($_COOKIE['loggedinextension']))
        {
            echo "qrtyueasesyti23sdiSdoi983";
        }
    }
?>