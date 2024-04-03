<?php
$id = isset($_GET['id'])?$_GET['id']:'fhhk';
$tv = [
  'fhzx' => '7c96b084-60e1-40a9-89c5-682b994fb680',  //資 訊 台
  'fhzw' => 'f7f48462-9b13-485b-8101-7b54716411ec',  //中 文 台
  'fhhk' => '15e02d92-1698-416c-af2f-3e9a872b4d78',  //香 港 台
  ];
$url = "https://m.fengshows.com/api/v3/hub/live/auth-url?live_id={$tv[$id]}&live_qa=FHD";
$h=[
  'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 15_3 like Mac OS X)',  
  'token:eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiIzMWUzZmVjMC1lY2IzLTExZWQtOWUxNS1mM2FiZjliZjhkOTYiLCJuYW1lIjoiIiwidmlwIjowLCJqdGkiOiJqQm5nMXBvZlQiLCJpYXQiOjE2ODM0NDg5ODksImV4cCI6MTY4NjA0MDk4OX0.0r8PuLetMiusCJul2tuPRzU8fnhxhqxBoycDV0_vKxI',
  ];
$cont = stream_context_create(['http'=>['header'=>$h]]);
$playurl = json_decode(file_get_contents($url, null, $cont))->data->live_url;
header('Location:'.$playurl);
//echo $playurl;
?>
