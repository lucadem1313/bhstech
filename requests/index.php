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
            $('.dismissalform').slideUp(0);
            $("select[name='topic']").change(function(){
                $('#searchedit form').submit();
            });
            $("select[name='filter']").change(function(){
                $('#searchedit form').submit();
            });
            $("select[name='sortby']").change(function(){
                $('#searchedit form').submit();
            });
            
            $(".approve").click(function(){
                element = $(this);
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {approve: $(this).parents('.card').data('id')},
                    success: function(response){
                        element.remove();
                    }
                });
            });
            
            $(".done").click(function(){
                element = $(this);
                element.siblings(".messageform").slideUp('medium');
                element.siblings(".dismissalform").slideToggle('medium');
            });
            
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
                element.siblings(".dismissalform").slideUp('medium');
            });
            $(".messageform").submit(function(e){
                e.preventDefault();
                element = $(this);
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {message: true, to: $(this).parents('.card').data('userid'), ticketid: $(this).parents('.card').data('id'), text: $(this).children('textarea').val()},
                    success: function(response){
                        element.children('textarea').val("");
                        element.slideToggle('medium');
                    }
                });
            });
            $(".dismissalform").submit(function(e){
                e.preventDefault();
                element = $(this);
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {finish: $(this).parents('.card').data('id'), duration: $(this).children('input[name="time"]').val(), difficulty: $(this).children('input[type="range"]').val(), ticketid: $(this).parents('.card').data('id'), text: $(this).children('textarea').val()},
                    success: function(response){
                        element.children('textarea').val("");
                        element.children('input:not([type="submit"])').val("");
                        element.slideToggle('medium');
                        element.siblings('.done').remove();
                    }
                });
            });
            
            
            
            
            
            $(".dismissalform input[type='range']").on("change mousemove",function(){
                if($(this).val() != ('10'||10))
                    $(this).siblings(".diff").html($(this).val() + "/10&nbsp;&nbsp;");
                else
                    $(this).siblings(".diff").text($(this).val() + "/10");
            });
        });
    </script>
</head>

<body>

<?php include($relative."includes/header.php"); ?>

<div id='container'>

        <div id='searchedit'>
            <form method='get'>
                <select name='topic' class='textinput'>
                    <?php
                    
                        if(isset($_GET['topic']))
                        {
                            if($_GET['topic'] != "all")
                            {
                                echo "<option value='".$searchtopicname."'>".$searchtopicname."</option>";
                                echo "<option value='all'>All Topics</option>";
                                $selecttopics = mysql_query("SELECT * FROM topics WHERE name <> '$searchtopicname'");
                                
                                
                                while($row = mysql_fetch_array($selecttopics))
                                {
                                    echo "<option value='".$row{'name'}."'>".$row{'name'}."</option>";
                                }
                            }
                            else
                            {
                                echo "<option value='all'>All Topics</option>";
                                $selecttopics = mysql_query("SELECT * FROM topics");
                                
                                while($row = mysql_fetch_array($selecttopics))
                                {
                                    echo "<option value='".$row{'name'}."'>".$row{'name'}."</option>";
                                }
                            }
                        }
                        else
                        {
                            echo "<option value='all'>All Topics</option>";
                            $selecttopics = mysql_query("SELECT * FROM topics");
                            
                            while($row = mysql_fetch_array($selecttopics))
                            {
                                echo "<option value='".$row{'name'}."'>".$row{'name'}."</option>";
                            }
                        } ?>
                </select>
                <select name='filter' class='textinput'>
                    <?php
                        
                        
                        if(isset($_GET['filter']))
                        {
                            echo "<option value='".$_GET['filter']."'>".$_GET['filter']."</option>";
                            
                            echo "<option value='All'>All</option>";
                            echo "<option value='Unreplied'>Unreplied</option>";
                        }
                        else
                        {
                            echo "<option value='Unreplied'>Unreplied</option>";
                            echo "<option value='All'>All</option>";
                        }
                        
                        ?>
                </select>
                <select name='sortby' class='textinput'>
                <?php
                        if(isset($_GET['sortby']))
                        {
                            echo "<option value='".$_GET['sortby']."'>".$_GET['sortby']."</option>";
                            
                            echo "<option value='Relevant'>Relevant</option>";
                            echo "<option value='Newest'>Newest</option>";
                            echo "<option value='Oldest'>Oldest</option>";
                        }
                        else
                        {
                            echo "<option value='Relevant'>Relevant</option>";
                            echo "<option value='Newest'>Newest</option>";
                            echo "<option value='Oldest'>Oldest</option>";
                        }
                        
                    ?>
                </select>
            </form>
        </div>
        <br><br><br><br>
        <?php
        
            $sortbysql = " ORDER BY relevance ASC";
            
            if(isset($_GET['sortby']))
            {
                if($_GET['sortby'] == 'Newest')
                {
                    $sortbysql = " ORDER BY id DESC";
                }
                else if($_GET['sortby'] == 'Oldest')
                {
                    $sortbysql = " ORDER BY id ASC";
                }
                else if($_GET['sortby'] == 'Relevant')
                {
                    $sortbysql = " ORDER BY relevance ASC";
                }
            }
            if(isset($_GET['filter']))
            {
                if($_GET['filter'] == "All")
                {
                    if(isset($searchtopicnumber)){
                        $selecttickets = mysql_query("SELECT * FROM tickets WHERE topic='$searchtopicnumber'".$sortbysql);
                    }
                    else
                        $selecttickets = mysql_query("SELECT * FROM tickets".$sortbysql);
                }
                else if($_GET['filter'] == "Unreplied")
                {
                    if(isset($searchtopicnumber)){
                        $selecttickets = mysql_query("SELECT * FROM tickets WHERE dismissed=0 AND topic='$searchtopicnumber'".$sortbysql);
                    }
                    else
                        $selecttickets = mysql_query("SELECT * FROM tickets WHERE dismissed=0".$sortbysql);
                }
            }
            else
            {
                if(isset($searchtopicnumber)){
                    $selecttickets = mysql_query("SELECT * FROM tickets WHERE dismissed=0 AND topic='$searchtopicnumber'".$sortbysql);
                }
                else
                    $selecttickets = mysql_query("SELECT * FROM tickets WHERE dismissed=0".$sortbysql);
            }
            
            
            if(mysql_num_rows($selecttickets) < 1)
                $noresults = true;
            while($row = mysql_fetch_array($selecttickets))
            {
                $topicname = "Other";
                $selecttopic = mysql_query('SELECT * FROM topics WHERE id='.$row{'topic'});
                while($row2 = mysql_fetch_array($selecttopic))
                {
                    $topicname = $row2{'name'};
                }


                $selectpostername = mysql_query('SELECT * FROM users WHERE id='.$row{'userid'});
                while($row2 = mysql_fetch_array($selectpostername))
                {
                    $postername= $row2{'firstname'}." ".$row2{'lastname'};
                }

                echo "<div class='card three' data-id='".$row{'id'}."' data-userid='".$row{'userid'}."'><h1>".$row{'title'}." - under <a href='?topic=".$topicname."'>".$topicname."</a></h1><p>".$row{'description'}."<br><br>In <b>".$row{'roomnumber'}."</b><br>By <b>".$postername."</b></p>";
                
                if($row{'dismissed'} == 0)
                    echo "<i class='material-icons done' title='Mark As Done'>done</i>&nbsp;&nbsp;&nbsp;&nbsp;";
                    
                echo "<i class='material-icons reply' title='Respond'>question_answer</i>";
                
                if($row{'approved'} == 0)
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;<i class='material-icons approve' title='Approve'>check_circle</i>";
                    
                echo "&nbsp;&nbsp;&nbsp;&nbsp;<i class='material-icons delete' title='Delete'>delete</i>
                <form class='messageform'>
                    <textarea name='message' class='textinput'></textarea><br><br>
                    <input type='submit' class='button' value='Send'>
                </form>";
                
                if($row{'dismissed'} == 0)
                {
                    echo "<form class='dismissalform'>
                    <br>
                        Difficulty solving: <span class='diff'>5/10&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;<input type='range' min='0' max='10'><br><br>
                        Time taken (min.): &nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='time' size='3' class='textinput'><br><br>
                        <textarea name='message' class='textinput' placeholder='Description of problem solution...'></textarea><br><br>
                        <input type='submit' class='button' value='Send'>
                    </form>
                    
                    </div>";
                }
                
                $checkifviewed = mysql_query("SELECT * FROM ticketviews WHERE userid=$userid AND ticketid=".$row{'id'}."");
                
                if(mysql_num_rows($checkifviewed) < 1)
                    mysql_query("INSERT INTO ticketviews (userid, ticketid) VALUES ('".mysql_real_escape_string($userid)."', '".mysql_real_escape_string($row{'id'})."')");
            }
            if($noresults)
            {
                echo "<div class='card one'><h1>No results found!</h1></div>";
            }
        ?>
    
</div><?php //include($relative."includes/footer.php"); ?>
</body>
</html>
<?php

    mysql_close();
?>