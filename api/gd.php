<?php
error_reporting(0);
$id = isset($_GET['id'])?$_GET['id']:'gdws';
$n = [
'gdws' => 43, //广东卫视
'gdzj' => 44, //广东珠江
'gdxw' => 45, //广东新闻
'gdms' => 48, //广东民生
'gdty' => 47, //广东体育
'nfws' => 51, //大湾区卫视
'jjkj' => 49, //经济科教
'gdys' => 53, //广东影视
'gdzy' => 16, //广东综艺
'gdgj' => 46, //广东国际
'gdse' => 54, //广东少儿
'jjkt' => 66, //嘉佳卡通
'nfgw' => 42, //南方购物
'lnxq' => 15, //岭南戏曲
'gdfc' => 67, //广东房产
'xdjy' => 13, //现代教育
'gdyd' => 74, //广东移动
'gdjk' => 99, //GRTN健康频道
'gdwh' => 75, //GRTN文化频道
'gdsh' => 102, //GRTN生活频道
'gdjlp' => 94, //GRTN纪录片
'gdlzt' => 100, //荔枝台
];
$ts = time().'790';
$headers = [
"content-type: application/json",
"referer: https://www.gdtv.cn/",
"origin: https://www.gdtv.cn",
"user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36",
"x-itouchtv-ca-key: 89541443007807288657755311869534",
"x-itouchtv-ca-timestamp: $ts",
"x-itouchtv-client: WEB_PC",
"x-itouchtv-device-id: WEB_".createNewGUID(),
];

$url = "https://tcdn-api.itouchtv.cn/getParam";
$sign = base64_encode(hash_hmac("SHA256","GET\n$url\n$ts\n","dfkcY1c3sfuw0Cii9DWjOUO3iQy2hqlDxyvDXd1oVMxwYAJSgeB6phO8eW1dfuwX",true));
$headers[] = "x-itouchtv-ca-signature: $sign";
$node = json_decode(get($url,$headers))->node;

//进入wss取串
$contextOptions = ['ssl' => ["verify_peer"=>false,"verify_peer_name"=>false,]];
$context = stream_context_create($contextOptions);
$sock = stream_socket_client("ssl://tcdn-ws.itouchtv.cn:3800",$errno,$errstr,1,STREAM_CLIENT_CONNECT,$context);
stream_set_timeout($sock,1);

$wssData = ['route'=>'getwsparam','message'=>$node];
$wssData = json_encode($wssData);
$key = genSecKey();
$header = "GET /connect HTTP/1.1\r\n";
$header.= "Host: tcdn-ws.itouchtv.cn:3800\r\n";
$header.= "Connection: Upgrade\r\n";
$header.= "Cache-Control: no-cache\r\n";
$header.= "Upgrade: websocket\r\n";
$header.= "Origin: https://www.gdtv.cn\r\n";
$header.= "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36\r\n";
$header.= "Sec-WebSocket-Version: 13\r\n";
$header.= "Sec-WebSocket-Key: {$key}\r\n";
$header.= "Sec-WebSocket-Extensions: permessage-deflate; client_max_window_bits\r\n";
fwrite($sock,$header."\r\n");
$handshake = stream_get_contents($sock);
if(strstr($handshake,'Sec-Websocket-Accept')){
fwrite($sock, encode($wssData));
$param = stream_get_contents($sock);
$param = substr($param,4);
$json =json_decode($param);
$wsnode = $json->wsnode;
}
//wss取串结束.

$burl = "https://gdtv-api.gdtv.cn/api/tv/v2/tvChannel/$n[$id]?tvChannelPk=$n[$id]&node=".base64_encode($wsnode);
$sign = base64_encode(hash_hmac("SHA256","GET\n$burl\n$ts\n","dfkcY1c3sfuw0Cii9DWjOUO3iQy2hqlDxyvDXd1oVMxwYAJSgeB6phO8eW1dfuwX",true));
$opt_headers = [
"access-control-request-headers: content-type,x-itouchtv-ca-key,x-itouchtv-ca-signature,x-itouchtv-ca-timestamp,x-itouchtv-client,x-itouchtv-device-id",
"access-control-request-method: GET",
"origin: https://www.gdtv.cn",
"referer: https://www.gdtv.cn",
"user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36",
];

$ch = curl_init($burl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "OPTIONS");	 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt($ch, CURLOPT_HTTPHEADER,$opt_headers);
$data = curl_exec($ch);
curl_close($ch); 

array_pop($headers);
$headers[] = "x-itouchtv-ca-signature: $sign";
$playurl = json_decode(json_decode(get($burl,$headers))->playUrl)->hd;
fclose($sock);// 取串完成再关闭wss

//m3u8清单referer校验。
$data = get($playurl,["Referer: https://www.gdtv.cn"]);
header("Content-Type: application/vnd.apple.mpegurl");
header("Content-Disposition: attachment; filename=index.m3u8");
echo $data;

function get($url,$header){
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
   curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
   $data = curl_exec($ch);
   curl_close($ch);
   return $data;
   }

function genSecKey(){
   return base64_encode(substr(md5(mt_rand(1,999)),0,16));
   }

function encode($data){
   $len = strlen($data);
   $head[0] = 129; 
   $mask = [];
   for ($j = 0; $j < 4; $j ++){
   $mask[] = mt_rand(1, 128);
   }
   $split = str_split(sprintf('%016b', $len), 8);
   $head[1] = 254;
   $head[2] = bindec($split[0]);
   $head[3] = bindec($split[1]);
   $head = array_merge($head, $mask);
   foreach ($head as $k => $v){
   $head[$k] = chr($v);
   }
   $mask_data = '';
   for ($j = 0; $j < $len; $j ++){
   $mask_data .= chr(ord($data[$j]) ^ $mask[$j % 4]);
   }
   return implode('', $head).$mask_data;
   }

function createNewGUID(){
   if (function_exists('com_create_guid') === true)return trim(com_create_guid(), '{}');
   return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
   }
?>
