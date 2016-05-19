<?php

    $relative = '../';
    $currentpage = 'View Help Requests';
    include($relative.'includes/start.php');
    
    if(!$mod)
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
            $('.messageform').slideUp(0);

            
            $(".delete").click(function(){
                element = $(this);
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {delete: $(this).parents('.card').data('id')},
                    success: function(response){
                        element.parents(".card").fadeOut('medium', function(){$(this).remove();});
                    }
                });
            });
            $(document).on("click", ".upgrade" , function(){
                element = $(this);
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {upgrade: $(this).parents('.card').data('id')},
                    success: function(response){
                        element.toggleClass('downgrade');
                        element.toggleClass('upgrade');
                        
                        element.attr('title', 'Remove Mod Status');
                        element.attr('style', 'color:#0AD252');
                    }
                });
            });
            $(document).on("click", ".downgrade" , function(){
                element = $(this);
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {downgrade: $(this).parents('.card').data('id')},
                    success: function(response){
                        element.toggleClass('downgrade');
                        element.toggleClass('upgrade');
                        element.attr('title', 'Make Into Mod');
                        element.attr('style', 'color:#000');
                        
                    }
                });
            });
            $(".reply").click(function(){
                element = $(this);
                
                element.siblings(".messageform").slideToggle('medium');
            });
            $(".messageform").submit(function(e){
                e.preventDefault();
                element = $(this);
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {message: true, to: $(this).parents('.card').data('id'), text: $(this).children('textarea').val()},
                    success: function(response){
                        element.children('textarea').val("");
                        element.slideToggle('medium');
                    }
                });
            });
        });
    </script>
</head>

<body>

<?php include($relative."includes/header.php"); ?>

<div id='container'>

        
        <?php
        
            
            $selecttickets = mysql_query("SELECT * FROM users ORDER BY karma DESC");
            
            

                
            while($row = mysql_fetch_array($selecttickets))
            {
                $selectmod = mysql_query('SELECT * FROM moderators WHERE userid='.$row{'id'});
                if(mysql_num_rows($selectmod) < 1)
                {
                    $ismod = false;
                }
                else
                {
                    $ismod = true;
                }
                
                $fullname = $row{'firstname'}." ".$row{'lastname'}." (".$row{'username'}.")";
                
                echo "<div class='card three' data-id='".$row{'id'}."'><h1>".$fullname."</h1><p>".$row{'email'}."</p>";
                    
                echo "<i class='material-icons reply' title='Message'>question_answer</i>";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$relative."user?id=".$row{'username'}."' style='color:#000;'><i class='material-icons user' title='View Info'>visibility</i></a>";
                
                if(!$ismod)
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;<i class='material-icons delete' title='Delete'>delete</i>";
                else if($godmode)
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;<i class='material-icons delete' title='Delete'>delete</i>";
                
                if(!$ismod && $mod)
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;<i class='material-icons upgrade' title='Make Into Mod'>verified_user</i>";
                
                if($godmode && $ismod)
                {
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;<i class='material-icons downgrade' style='color:#0AD252;' title='Remove Mod Status'>verified_user</i>";
                }
                    
                echo "<form class='messageform'>
                    <textarea name='message' class='textinput'></textarea><br><br>
                    <input type='submit' class='button' value='Send'>
                </form>
                
                </div>";
            }
        ?>
    
</div>
<?php include($relative."includes/footer.php"); ?>
</body>
</html>
<?php

    mysql_close();
?>