<?php
header("Content-Type: text/json; charset=UTF-8");
$id = isset($_GET['id'])?$_GET['id']:'fhzx';
$tv = array(
  'fhzx' => '4',  //資 訊 台
  'fhzw' => '5',  //中 文 台
  'fhhk' => '6',  //香 港 台
  );
$url = 'http://m.fengshows.com/api/v3/live?live_type=tv';
$response = get_data($url);
$channels = json_decode($response);
foreach ($channels as $channel) {
  if($channel->order==$tv[$id]){
    $channelId = $channel->_id;
    break; 
  }    
}
$info = get_url($channelId,'FHD');
if($info->status !== '0'){
  $info = get_url($channelId,'HD');
}
$liveUrl = $info->data->live_url;
header('Location:'.$liveUrl);

function get_url($cid, $qa){
  $url = "https://m.fengshows.com/api/v3/hub/live/auth-url?live_id={$cid}&live_qa={$qa}";
  $response = get_data($url);
  $data = json_decode($response);
  return $data;
}
function get_data($url){
$header=array(
  'fengshows-client: app(ios,5040718)',
  'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 15_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/98.0.4758.85 Mobile/15E148 Safari/604.1',
  'token:eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI0OTQ3M2U4MC00Yjc3LTExZWMtYjM4My0xZDkxOWNhOTViOWQiLCJuYW1lIjoiVjZXaW5kIiwidmlwIjowLCJqdGkiOiJYWTI1MXEtdl8iLCJyZWdpc3RyYXRpb25faWQiOiIxNzE5NzZmYThhNDFmMjFlNDI4IiwiaWF0IjoxNzQxMDc0MTA0LCJleHAiOjE3NDM2NjYxMDR9._6Cgt6S1dxcRhxfTO0DK1nZG3Hq9V-M8lXJL-1nO-3k', 
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
$data = curl_exec($ch);
curl_close($ch);
return $data;
}
