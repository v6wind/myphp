<?php
/*
广州综合，id=31
广州新闻，id=32
南国都市，id=33
广州法治，id=34
广州新闻，id=35
广州影视，id=36
*/

$data = json_decode(file_get_contents("https://gzbn.gztv.com:7443/plus-cloud-manage-app/liveChannel/queryLiveChannelList?type=1"))->data;//id=31-36
$count = count($data);
for($i=0;$i<$count;$i++){
if($data[$i]->stationNumber == $_GET["id"]){
$playurl = $data[$i]->httpUrl;
break;
}}
header("Location: {$playurl}",true,302);
?>
