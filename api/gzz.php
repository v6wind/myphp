<?php
$id = isset($_GET['id']) ? $_GET['id'] : 'zh';
$n = [
'zh' => 'zhonghe',//广州综合
'xw' => 'xinwen',//广州新闻
'js' => 'jingsai',//广州竞赛
'ys' => 'yingshi',//广州影视
'fz' => 'fazhi',//广州法治
'ds' => 'shenghuo',//南国都市
];
$url = 'https://www.gztv.com/gztv/api/tv/'.$n[$id];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$data = curl_exec($ch);
curl_close($ch);
$re = json_decode($data)->data;
header('Location: '.$re);
?>
