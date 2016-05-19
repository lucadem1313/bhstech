<?php

    $relative = '../';
    $currentpage = 'Settings';
    include($relative.'includes/start.php');
    
    if(!$loggedin)
    {
        header("Location: ".$relative);
    }
    else
    {
        $selectinfo = mysql_query("SELECT * FROM users WHERE id=$userid");
        while($row = mysql_fetch_array($selectinfo))
        {
            $firstname = $row{'firstname'};
            $lastname = $row{'lastname'};
            $roomnumber = $row{'mainroom'};
            $email = $row{'email'};
        }
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
                if(string.length < 4 || !parseInt(string))
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
            
            
            $("form").submit(function(e){
                e.preventDefault();
                
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {
                        'firstname': $('input[name="firstname"]').val(),
                        'lastname': $('input[name="lastname"]').val(),
                        'username': $('input[name="username"]').val(),
                        'roomnumber': $('input[name="roomnumber"]').val()
                        },
                    success: function(response){
                        window.location.reload();
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
            <h1>Settings</h1>
            <form action='' method='post'>
                <input type='text' name='firstname' class='textinput' placeholder="Firstname..." value="<?php echo $firstname; ?>">
                <input type='text' name='lastname' class='textinput' placeholder="Lastname..."  value="<?php echo $lastname; ?>"><br><br>
                <input type='text' name='username' class='textinput' placeholder="Username..."  value="<?php echo $username; ?>">
                &nbsp;&nbsp;&nbsp;<?php echo $email; ?><br><br>
                Main Room #&nbsp&nbsp&nbsp&nbsp<input type='text' size='3' name='roomnumber' class='textinput' value="<?php echo $roomnumber; ?>"><br><br>

                <input type='submit' value='Save Info' class='button'>
                <span id='error'><?php
                    if($error == 1)
                        echo "Username is taken";
                    else if($error == 2)
                        echo "Password is too short";
                    else if($error == 3)
                        echo "Please fill all required fields";
                    else if($error == 4)
                        echo "Error connecting";
                ?></span>
            </form><br>
            <a href='../changepass'>Change Password</a>
        </div>
    
</div>
<?php include($relative."includes/footer.php"); ?>
</body>
</html>
<?php

    mysql_close();
?>