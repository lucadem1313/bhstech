<?php

    $relative = '../';
    $currentpage = 'Change Password';
    include($relative.'includes/start.php');
    
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
            $("input[name='password2']").keyup(function(){
                var string = $(this).val();
                var errorMsg = "Passwords don't match";
                var element = $(this);
                if(string != $("input[name='password']").val())
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
            
            $("form").submit(function(e){
                e.preventDefault();
                
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {
                        originalpass: $('input[name="originalpassword"]').val(),
                        newpass: $('input[name="password"]').val(),
                        confirmpass: $('input[name="password2"]').val()
                        },
                    success: function(response){
                        console.log(response);
                        if(response.indexOf('error1') != -1)
                            $('#error').text("Passwords don't match");
                        else if(response.indexOf('error2') != -1)
                            $('#error').text("Old password is incorrect");
                        else
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
            <h1>Change Password</h1>
            <form action='' method='post'>
                &nbsp;&nbsp;&nbsp;&nbsp;<input type='password' name='originalpassword' class='textinput' placeholder="Current Password..."><br><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<input type='password' name='password' class='textinput' placeholder="New Password..."><br><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<input type='password' name='password2' class='textinput' placeholder="Confirm Password..."><br><br>

                <input type='submit' value='Save' class='button'>
                <span id='error'></span>
            </form><br>
        </div>
    
</div>
<?php include($relative."includes/footer.php"); ?>
</body>
</html>
<?php

    mysql_close();
?>