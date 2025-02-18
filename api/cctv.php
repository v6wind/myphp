<?php
error_reporting(0);
$id = $_GET['id']??'cctv1';
$q = $_GET['q']??'lg';
$n = [
    'cctv1' => ['11200132825562653886','0916dc838fce69425b9aed6d3e790f49'],//CCTV1
    'cctv2' => ['12030532124776958103','102ebab86b52dcb7d437aa64cac55223'],//CCTV2
    'cctv4' => ['10620168294224708952','bbb5af21098ef891310f3e95aca7eaa0'],//CCTV4
    'cctv13' => ['16265686808730585228','563e1000aabda6bdda96248302d34051'],//CCTV13
    ];
$m = [
    'lg' => 1, //蓝光
    'cq' => 4, //超清
    'gq' => 8, //高清
    ];
$t =  time();
$w = "&&&20000009&{$n[$id][1]}&{$t}&emas.feed.article.live.detail&1.0.0&&&&&";
$k = "emasgatewayh5";
$sign = hash_hmac('sha256', $w, $k);
$url = "https://emas-api.cctvnews.cctv.com/h5/emas.feed.article.live.detail/1.0.0?articleId={$n[$id][0]}&scene_type=6";
$h = [
     'x-emas-gw-appkey: 20000009',
     'x-emas-gw-pv: 6.1',
     'x-emas-gw-sign:' .$sign,
     'x-emas-gw-t:' .$t,
     ];
$data = get($url,$h);
$response = json_decode(base64_decode(json_decode($data,1)['response']),1);
$live = $response['data'] ['live_room']['liveCameraList'][0]['pullUrlList'][$m[$q]]['authResultUrl'][0]['authUrl'];
$burl = "https://live-play.cctvnews.cctv.com/cctv/";
$php = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on'?"https":"http")."://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$ts = $_GET['ts'];
if(!$ts){
   header('Content-Type: application/vnd.apple.mpegurl');
   print_r(preg_replace("/(.*?.ts)/i",$php."?ts=$burl$1",m3u8(trim($live))));
   } else {
     $data = m3u8(trim($ts));
     header('Content-Type: video/MP2T');
     echo $data;
     }
function get($url,$header){
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
     $res = curl_exec($ch);
     curl_close($ch);
     return $res;
     }
function m3u8($url){
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_REFERER, 'https://emas-api.cctvnews.cctv.com/');
     $res = curl_exec($ch);
     curl_close($ch);
     return $res;
     }
?>
