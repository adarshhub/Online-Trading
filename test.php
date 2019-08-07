<?php

require 'config/config.php';

$balance_update = mysqli_query($con,"INSERT INTO balance  VALUES ('eth', '10', 'shibam') ON DUPLICATE KEY UPDATE  amount= 10" );
echo $balance_update;
?>