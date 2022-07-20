<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no');

$redis = new \Redis();
$redis->connect( '127.0.0.1' , 6379 );
$arList = $redis->lrange( "newData", 0, - 1 );
$time = time();

echo "data: ".$arList[1]." \n\n";

//if( count($arList) > 0){
//	echo "data: ".$arList[1]." \n\n";
//	$arList = $redis->del( "newData" );
//} else {
//	echo "data: $time \n\n\"";
//}
//echo $_SESSION['active'] == $arList[0] ? "data: $time \n\n\"" :  "data: ".$arList[1]." \n\n";
//$_SESSION['active'] = $arList[0];
//$arList = $redis->del( "newData" );
flush();
sleep(5);
?>

