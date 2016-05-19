<?php

    $relative = '';
    $currentpage = 'Home';
    include($relative.'includes/start.php');
    
?>







<!DOCTYPE html>

<html>
<head>
    <?php include($relative."includes/head.php"); ?>
</head>

<body>

    <?php include($relative."includes/header.php"); ?>

    <div id='container'>
        
        <?php
            $selecttickets = mysql_query("SELECT * FROM tickets WHERE dismissed=0 AND approved=1");
            if(mysql_num_rows($selecttickets) > 0)
            {
                echo " <div class='card three'><h1>Problems being reported now:</h1><ul>";
            }
            while($row = mysql_fetch_array($selecttickets))
            {
                echo "<li>".$row{'roomnumber'}.": ".$row{"title"}."</li>";
            }
            if(mysql_num_rows($selecttickets) > 0)
            {
                echo "</ul></div>";
            }
        ?>
    
        <div class='card three'>
            <h1>Frequent Problems:</h1>
            <ul>
                <br>
                <li><b>My Chromebook won't connect!</b><br>
                      <ul>
                           <li>Make sure you are trying to connect with a <i>school</i> Chromebook</li>
                           <li>Make sure you are connecting to the "Chromebooks" network</li>
                           <li>Bring your phone to the tech office (in the library), or if you are in a class send a help request</li>
                      </ul>
                </li>
                <br>
                <li><b>My Phone won't connect!</b><br>
                      <ul>
                           <li>Turn in the device agreement</li>
                           <li>Make sure you are trying to connect to BHS_PTD</li>
                           <li>Go to <a href='http://apple.com'>www.apple.com</a> and login</li>
                           <li>Bring your phone to the tech office (in the library)</li>
                      </ul>
                </li>
                <br>
                <li><b>I can't print my document!</b><br>
                      <ul>
                           <li>Make sure you are not using your Chromebook to print</li>
                           <li>Are you printing to the right printer?</li>
                           <li>Try a different computer/printer</li>
                      </ul>
                </li>
            </ul>
            <!--<div class='minicard'>
                <img src=''>
            </div>-->
        </div>
        <div class='card three'><h1>Now what are all those networks for?</h1><br>
            <ul>
                <li><b>Student_PLD</b>: This network is for student devices, but not for chromebooks. To connect to this network, you must sign and turn in the personal device agreement.</li><br>
                <li><b>Staff_PTD</b>: The network for staff devices used in classrooms. Students do not need to be concerned with this network, and cannot connect.</li><br>
                <li><b>Chromebooks</b>: This is the network for BHS Chromebooks, and they are already configured to connect to this network.This is not for non-school devices.</li><br>
                <li><b>Guest</b>: This network is used for visitors to BHS during events, not students. This network will only be available during the events.</li><br>
                <li><b>B0wNET</b>: This network is not being used at the moment. Do not try to connect.</li>
            </ul>
        </div>
        <!--<div class='card three'>
            <h1>Creators:</h1>
            <p><b>Luca Demian</b> - The developer and designer of BHS Tech Help<br><br><b>Joe Savell</b> - Helps write stuff<<br><br><b>Kaleb Crowther</b> - Also helps write stuff</p>
        </div>-->
    </div><?php include($relative."includes/footer.php"); ?>
</body>
</html>
<?php

    mysql_close();
?>