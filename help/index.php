<?php

    $relative = '../';
    $currentpage = 'Get Help';
    include($relative.'includes/start.php');
    
    if(isset($_POST['checkusername']))
    {
        if(mysql_num_rows(mysql_query("SELECT * FROM users WHERE username=".$_POST['checkusername'])) > 0)
        {
            echo true;
        }
        else
        {
            echo false;
        }
    }
    
    
    $error = 0;
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
                    $insertinfo = mysql_query("INSERT INTO tickets (title, description, topic, roomnumber, userid, inclass, classdependant, relevance) VALUES ('".mysql_real_escape_string($title)."', '".mysql_real_escape_string($description)."', '".mysql_real_escape_string($topic)."', '".mysql_real_escape_string($roomnumber)."', '".mysql_real_escape_string($userid)."', '".mysql_real_escape_string($inclass)."', '".mysql_real_escape_string($depend)."', '".mysql_real_escape_string($equation)."')");
                    
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
    
    
    
    if($loggedin)
    {
        $selectroomnum = mysql_query("SELECT * FROM users WHERE id='$userid'");
        while($row = mysql_fetch_array($selectroomnum))
        {
            $roomnumsaved = $row{'mainroom'};
        }
    }
    else
    {
        header("Location: ".$relative);
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
                if(string.length < 1)
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
                        console.log(response);
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
            <h1>Send for help!</h1>
            <form action='' method='post'>
                <input type='text' name='title' class='textinput' placeholder="Title..." value='<?php if(isset($_POST['title'])){echo $_POST['title']; }?>'>
                <select name='topic' class='textinput'>
                    <?php
                    
                    
                        $selecttopics = mysql_query("SELECT * FROM topics");
                        while($row = mysql_fetch_array($selecttopics))
                        {
                            echo "<option value='".$row{'id'}."'>".$row{'name'}."</option>";
                        }
                        
                    ?>
                </select><br><br>
                <textarea name='description' placeholder='Description...' rows='6' cols='37' class='textinput'><?php if(isset($_POST['description'])){echo $_POST['description']; }?></textarea><br><br>
                Room &nbsp&nbsp&nbsp&nbsp<input type='text' size='3' name='roomnumber' class='textinput' <?php if(isset($_POST['roomnumber'])){echo $_POST['roomnumber']; } else if(isset($roomnumsaved)){echo $roomnumsaved;}?>><br><br>
                <input type='hidden' name='inclass' value='false'>
                <input type='checkbox' name='inclass' value='true'> Are you in class?<br><br>
                <input type='hidden' name='depend' value='false'>
                <input type='checkbox' name='depend' value='true'> Does your class depend on this?<br><br>
                
                <input type='submit' value='Send Request' class='button'>
                <span id='error'><?php
                    if($error == 1)
                        echo "Cannot connect";
                    else if($error == 2)
                        echo "Invalid characters";
                    else if($error == 3)
                        echo "Please fill all required fields";
                ?></span>
            </form><br>
        </div>
    
</div>
<?php include($relative."includes/footer.php"); ?>
</body>
</html>
<?php

    mysql_close();
?>