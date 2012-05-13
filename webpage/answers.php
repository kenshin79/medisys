<?php
$answer = $_POST['answer'];
$user = $_POST['user'];
$utype = $_POST['utype'];

date_default_timezone_set('Asia/Hong_Kong');
$newanswer = "----------\n".date('m/d/Y h:i:s a', time())." by ".$user." (".$utype.")\n".$answer."\n";
$file = "quiz_answers.txt";
$handle = fopen($file, 'a+');
fwrite($handle, $newanswer);

echo "Thank you for your answering the quiz. Feedback will be provided in the coming week.";