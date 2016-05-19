<?php

    $relative = '../';
    $currentpage = 'View Help Requests';
    include($relative.'includes/start.php');
    

    if(isset($_GET['id']))
        $user = $_GET['id'];
    else
        header("Location: ".$relative);
        

    if(!$mod && $user != $username)
    {
        header("Location: ".$relative);
    }


    $selectuserinfo = mysql_query("SELECT * FROM users WHERE username='$user'");
    if(mysql_num_rows($selectuserinfo) < 1)
        header("Location: ".$relative);
        
    while($row = mysql_fetch_array($selectuserinfo))
    {
        $selecteduserid = $row{'id'};
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
        
            
            $selecttickets = mysql_query("SELECT * FROM users WHERE username='$user' LIMIT 1");
            
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
                
                $fullname = $row{'firstname'}." ".$row{'lastname'};
                $datestarted = date('n/j/Y', strtotime($row{'date'}));
                
                
                echo "<div class='card one'>
                            <h1>User Info</h1>
                            <ul>
                                <li>Name: ".$fullname."</li>
                                <li>Username: ".$row{'username'}."</li>
                                <li>Email: ".$row{'email'}."</li>
                                <li>Karma: ".$row{'karma'}."</li>";
                                
                            if($row{'mainroom'} != ('0'||0))
                                echo "<li>Main Room: ".$row{'mainroom'}."</li>";
                                
                            echo "<li>Date Started: ".$datestarted."</li>
                            </ul>
                    </div>";
                
                $datecounts1 = array();
                $dates1 = array();
                $selectticketssolved = mysql_query("SELECT * FROM ticketaction WHERE userid=$selecteduserid ORDER BY date ASC");
                while($row2 = mysql_fetch_array($selectticketssolved))
                {
                    $datetocheck = date('Y-m-d', strtotime($row2{'date'}));
                    
                    $dateformatted = date('n/j/Y', strtotime($row2{'date'}));
                    
                    if(!in_array($dateformatted, $dates1))
                    {
                        $datatemp = mysql_num_rows(mysql_query("SELECT * FROM ticketaction WHERE date BETWEEN '".$datetocheck." 00:00:00' AND '".$datetocheck." 23:59:59' AND userid=$selecteduserid"));
                        array_push( $datecounts1, $datatemp);
                        array_push($dates1, $dateformatted);
                    }
                }
                
                echo "<div class='card three'><div id='chartDiv1'></div></div>
                <script>
                
                var labels1 = ".json_encode($dates1).";
                var data1 = ".json_encode($datecounts1).";
                  var chartData1={
                    'type':'line',
                    'plot':{
                        'animation':{
                            'effect':'1',
                            'sequence':'1',
                            'speed':'2'
                        }
                    },
                    'title':{
                        'text':'Help Requests User Has Solved Over Time'
                    },
                    'scale-x':{
                        'labels':labels1
                    },
                    'series':[
                        {
                            'values': data1
                        }
                    ]
                  };
                  zingchart.render({
                    id:'chartDiv1',
                    data:chartData1,
                    height:400,
                    width:600
                  });
                </script>";
                
                
                
                
                
                $datecounts2 = array();
                $dates2 = array();
                $selectticketssolved = mysql_query("SELECT * FROM messages WHERE fromuser=$selecteduserid ORDER BY date ASC");
                while($row2 = mysql_fetch_array($selectticketssolved))
                {
                    $datetocheck = date('Y-m-d', strtotime($row2{'date'}));
                    
                    $dateformatted = date('n/j/Y', strtotime($row2{'date'}));
                    
                    if(!in_array($dateformatted, $dates2))
                    {
                        $datatemp = mysql_num_rows(mysql_query("SELECT * FROM messages WHERE date BETWEEN '".$datetocheck." 00:00:00' AND '".$datetocheck." 23:59:59' AND fromuser=$selecteduserid"));
                        array_push( $datecounts2, $datatemp);
                        array_push($dates2, $dateformatted);
                    }
                }
                
                echo "<div class='card three'><div id='chartDiv2'></div></div>
                <script>
                
                var labels2 = ".json_encode($dates2).";
                var data2 = ".json_encode($datecounts2).";
                  var chartData2={
                    'type':'line',
                    'plot':{
                        'animation':{
                            'effect':'1',
                            'sequence':'1',
                            'speed':'2',
                            'delay':'4000'
                        }
                    },
                    'title':{
                        'text':'Messages User Has Sent Over Time'
                    },
                    'scale-x':{
                        'labels':labels2
                    },
                    'series':[
                        {
                            'values': data2
                        }
                    ]
                  };
                  zingchart.render({
                    id:'chartDiv2',
                    data:chartData2,
                    height:400,
                    width:600
                  });
                </script>";
                
                
                
                
                
                $datecounts3 = array();
                $dates3 = array();
                $selectticketssolved = mysql_query("SELECT * FROM ticketviews WHERE userid=$selecteduserid ORDER BY date ASC");
                while($row2 = mysql_fetch_array($selectticketssolved))
                {
                    $datetocheck = date('Y-m-d', strtotime($row2{'date'}));
                    
                    $dateformatted = date('n/j/Y', strtotime($row2{'date'}));
                    
                    if(!in_array($dateformatted, $dates3))
                    {
                        $datatemp = mysql_num_rows(mysql_query("SELECT * FROM ticketviews WHERE date BETWEEN '".$datetocheck." 00:00:00' AND '".$datetocheck." 23:59:59' AND userid=$selecteduserid"));
                        array_push( $datecounts3, $datatemp);
                        array_push($dates3, $dateformatted);
                    }
                }
                
                echo "<div class='card three'><div id='chartDiv3'></div></div>
                <script>
                
                var labels3 = ".json_encode($dates3).";
                var data3 = ".json_encode($datecounts3).";
                  var chartData3={
                    'type':'line',
                    'plot':{
                        'animation':{
                            'effect':'1',
                            'sequence':'1',
                            'speed':'2',
                            'delay':'8000'
                        }
                    },
                    'title':{
                        'text':'Number of Requests User Has Viewed Over Time'
                    },
                    'scale-x':{
                        'labels':labels3
                    },
                    'series':[
                        {
                            'values': data3
                        }
                    ]
                  };
                  zingchart.render({
                    id:'chartDiv3',
                    data:chartData3,
                    height:400,
                    width:600
                  });
                </script>";
            }
            
            
            
            
            $selectticketsbyuser = mysql_query("SELECT * FROM tickets WHERE userid=$selecteduserid ORDER BY date DESC");
            while($row = mysql_fetch_array($selectticketsbyuser))
            {
                $topicname = "Other";
                $selecttopic = mysql_query('SELECT * FROM topics WHERE id='.$row{'topic'});
                while($row2 = mysql_fetch_array($selecttopic))
                {
                    $topicname = $row2{'name'};
                }
                echo "<div class='card three' data-id='".$row{'id'}."' data-userid='".$row{'userid'}."'><h1>Submitted by ".$fullname.": ".$row{'title'}."</h1><p>".$row{'description'}."<br><br>In <b>RM ".$row{'roomnumber'}."</b><br>By: ".$fullname."</p></div>";
                
            }
            
            
            $selectticketssolvedbyuser = mysql_query("SELECT * FROM dismissal WHERE userid='$selecteduserid' ORDER BY date DESC");
            while($row2 = mysql_fetch_array($selectticketssolvedbyuser))
            {
                $formatteddate = date('n/j/Y', strtotime($row2{'date'}));
                $difficulty = $row2{'difficulty'};
                $duration = $row2{'duration'};
                $description = $row2{'description'};
                
                $selectticketsbyuser = mysql_query("SELECT * FROM tickets WHERE id='".$row2{'ticketid'}."' LIMIT 1");
                while($row = mysql_fetch_array($selectticketsbyuser))
                {
                    $topicname = "Other";
                    $selecttopic = mysql_query('SELECT * FROM topics WHERE id='.$row{'topic'});
                    while($row2 = mysql_fetch_array($selecttopic))
                    {
                        $topicname = $row2{'name'};
                    }
                    echo "<div class='card three'><h1>Solved by ".$fullname.": ".$row{'title'}."</h1><p>".$row{'description'}."<br><br>In <b>RM ".$row{'roomnumber'}."</b><br>";
                }
                if(mysql_num_rows($selectticketsbyuser) < 1)
                    echo "<div class='card three'><h1>Deleted help request</h1><p><i>Deleted</i><br>";
                    
                echo "<span class='divider'></span><br>Solved By: ".$fullname."<br>Difficulty: ".$difficulty."/10<br>Duration: ".$duration." Min.<br>Date: ".$formatteddate."<br><br>".$description."</p></div>";
            }
        ?>
    
</div>
<?php include($relative."includes/footer.php"); ?>
</body>
</html>
<?php

    mysql_close();
?>