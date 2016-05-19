<?php

    $relative = '../';
    $currentpage = 'Verify Account';
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
   
        <div class='card one'>
            <h1>Please Verify Account</h1>
            Now that your account has been created, go check your email for a message to verify your account.
        </div>
    
</div>
<?php include($relative."includes/footer.php"); ?>
</body>
</html>
<?php
    mysql_close();
?>