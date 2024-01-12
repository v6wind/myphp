<?php
/*
广州综合,id=zhonghe
广州新闻,id=xinwen
广州竞赛,id=jingsai
广州影视,id=yingshi
广州法治,id=fazhi
广州南国都市,id=shenghuo
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
