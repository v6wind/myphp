<?php
error_reporting(0);
$id = isset($_GET['id'])?$_GET['id']:'fhzx';
$fmt = isset($_GET['fmt'])?$_GET['fmt']:'hls';//hls flv
$n = [
  'fhzx' => '7c96b084-60e1-40a9-89c5-682b994fb680',  //资讯台
  'fhzw' => 'f7f48462-9b13-485b-8101-7b54716411ec',  //中文台
  'fhhk' => '15e02d92-1698-416c-af2f-3e9a872b4d78',  //香港台
  ];
if($fmt == 'hls') $url = "https://m.fengshows.com/api/v3/hub/live/auth-url?stream_type=hls&live_id={$n[$id]}&live_qa=HD";
if($fmt == 'flv') $url = "https://m.fengshows.com/api/v3/hub/live/auth-url?stream_type=flv&live_id={$n[$id]}&live_qa=HD";
$live = get_headers(json_decode(file_get_contents($url))->data->live_url,1)['Location'];
if($fmt == 'flv') {
   header('Location:'.$live);
   //echo $live;
   }
if($fmt == 'hls') {
   $burl = "http://qctv.fengshows.cn/live/";
   $cur = preg_replace("/(.*?.ts)/i", $burl."$1",file_get_contents($live));
   if($id == 'fhzw') $cur = $cur;
   else $cur = preg_replace("/48-/","72-",$cur);
   header("Content-Type: application/vnd.apple.mpegurl");
   print_r($cur);
   }
?>
