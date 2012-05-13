<?php
$fback = $_POST['fback'];
$user = $_POST['user'];
$utype = $_POST['utype'];

date_default_timezone_set('Asia/Hong_Kong');
$newfback = "----------<br/>".date('m/d/Y h:i:s a', time())." by ".$user." (".$utype.")<br/>".$fback."<br/>";
$file = "fback.txt";
$handle = fopen($file, 'a+');
fwrite($handle, $newfback);

echo "Thank you for your feedback. This will help us improve Medisys and other services.";
echo "<br/>";
echo "You can view other users' feedback <a href = \"http://192.168.81.27/medisys/webpage/view_fback.html\" >here</a>.";

