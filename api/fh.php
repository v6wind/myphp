<?php
error_reporting(0);
$id = isset($_GET['id'])?$_GET['id']:'fhzx';
$n = [
'fhzx' => '011dac7f-d5cf-4c71-92c5-f77e465a1e4f',//资讯
'fhws' => '62ba669b-9fe0-4d03-bf86-cc79f3f6948b',//卫视
'fhhk' => 'aa85d05d-7fe0-4452-a503-aa43d14873a4',//香港
];
$url = 'http://m.fengshows.com/api/v3/live?live_type=tv';
//备用:http://api.fengshows.cn/live/list-with-cover?live_type=tv
$res = file_get_contents($url);
$d = json_decode($res);
for($i=0;$i<4;$i++){
if($n[$id] == $d[$i] -> _id) {
$burl = $d[$i] -> live_url_fhd;
}
}
$n = preg_split('/.flv/',basename($burl))[0];
$hexTime = dechex(time());
$hash = md5("obb9Lxyv5C/live/".$n.$hexTime);
$playurl = 'http://tlive.fengshows.cn/live/'.$n.'.flv?txSecret='.$hash.'&txTime='.$hexTime;
header('Location:'.$playurl);
//echo $playurl;
?>
