<?php
if(isset($_POST)){
	file_put_contents('logs_post.txt',json_encode($_POST));
}
if(isset($_GET)){
	file_put_contents('logs_get.txt',json_encode($_GET));
}
$json = file_get_contents('php://input');
file_put_contents('logs_input.txt',$json);


/* $str=file_get_contents('logs_input.txt');
$cont=substr($str,5,strlen($str));
$cont=json_decode($cont);
file_put_contents('logs_input.txt',$cont);
$cont=json_decode($cont,true);
echo "<pre>";
var_dump($cont); */


