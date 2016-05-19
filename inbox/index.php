<?php

    $relative = '../';
    $currentpage = 'View Help Requests';
    include($relative.'includes/start.php');
    
    $noresults = false;
    
    if(isset($_GET['topic']))
    {
        if($_GET['topic'] != "all")
        {
            $searchtopicname = urldecode($_GET['topic']);
            
            $searchfortopic = mysql_query("SELECT * FROM topics WHERE name LIKE '%{$searchtopicname}%'");
            
            while($row = mysql_fetch_array($searchfortopic))
            {
                $searchtopicnumber = $row{'id'};
            }
            
            if(mysql_num_rows($searchfortopic) <1)
            {
                $noresults = true;
            }
        }
        
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
                    data: {message: true, to: $(this).parents('.card').data('userid'), text: $(this).children('textarea').val()},
                    success: function(response){
                        element.slideToggle('medium');
                        element.children('textarea').val("");
                    }
                });
            });
        });
    </script>
</head>

<body>

<?php include($relative."includes/header.php"); ?>

<div id='container'>

        <!--<div class='card one dark' style='margin-bottom:-10px;'><h1>Unread</h1></div>-->
        
        <?php
        
            $selecttickets = mysql_query("SELECT * FROM messages WHERE touser='$userid' AND deleted=0 ORDER BY id DESC");
            
            if(mysql_num_rows($selecttickets) < 1)
                $noresults = true;
                
            while($row = mysql_fetch_array($selecttickets))
            {
                $selectuserinfo = mysql_query("SELECT * FROM users WHERE id=".$row{'fromuser'});
                while($row2 = mysql_fetch_array($selectuserinfo))
                {
                    $fromuserfull = $row2{'firstname'}." ".$row2{'lastname'}." (".$row2{'username'}.")";
                }
                echo "<div class='card three' data-id='".$row{'id'}."' data-userid='".$row{'fromuser'}."'><p>".$row{'message'}."<br><br><b>From</b>: ".$fromuserfull."</p>";
                
                    
                echo "<i class='material-icons reply' title='Respond'>question_answer</i>";
                    
                echo "&nbsp;&nbsp;&nbsp;&nbsp;<i class='material-icons delete' title='Delete'>delete</i>
                <form class='messageform'>
                    <textarea name='message' class='textinput'></textarea><br><br>
                    <input type='submit' class='button' value='Send'>
                </form>
                
                </div>";
                

                mysql_query("UPDATE messages SET read=1 WHERE id=".$row{'id'}.")");
            }
            if($noresults)
            {
                echo "<div class='card one'><h1>No Messages!</h1></div>";
            }
        ?>
    
</div>
<?php include($relative."includes/footer.php"); ?>
</body>
</html>
<?php

    mysql_close();
?>