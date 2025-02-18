<?php
//Written by Wheiss
error_reporting(0);
date_default_timezone_set("PRC");
$hostip = $_GET['hostip']??'39.136.135.241';
$mode = $_GET['mode']??'1';
$ts = $_GET['ts']??'';
if ($ts){
	$decodedUts = urldecode($ts);
	$tsa = explode('AuthInfo=',$decodedUts);
	$authinfo = urlencode($tsa[1]);
	$decodedUts = $tsa[0].'AuthInfo='.$authinfo;
	$decodedUArray = parse_url($decodedUts);
	$url = str_replace($decodedUArray['host'],$hostip,$decodedUts);
	$headers = [
		"User-Agent: okhttp/5.0.0",
		"Host: ".$decodedUArray['host']
	];
	if ($mode==2){
		getts($url,$headers);
		exit;
	}
	$data = get($url,null,$headers);
	if ($data[1]!==200){
		$d = get($url,null,$headers)[0];
	} else {
		$d = $data[0];
	}
	header('Content-Type: video/MP2T');
	header("Content-Disposition: inline; filename={$ts}.ts");
} else {
	$u = $_GET['u']??'';
	$https = isset($_SERVER['HTTPS'])?'https':'http';//当前请求的主机使用的协议。
	$http_host = $_SERVER['HTTP_HOST'];//当前请求的主机名。
	$requestUri = $_SERVER['REQUEST_URI'];//获取当前请求的 URI
	$decodedUri = urldecode($requestUri);//URL解码
	$Uripath = explode('?',$decodedUri)[0];//strstr($decodedUri,'?',true);
	if ($u){
		$decodedU = urldecode($u);
		$urlpath = explode('index.m3u8',$decodedU)[0];
		$urlp = "{$https}://{$http_host}{$Uripath}?ts=";
		$decodedUArray = parse_url($decodedU);
		$url = str_replace($decodedUArray['host'],$hostip,$decodedU);
		$headers = [
			"User-Agent: okhttp/5.0.0",
			"Host: ".$decodedUArray['host']
		];
		$m3u8 = get($url,null,$headers)[0];
		if (strpos($m3u8,'EXTM3U')===false) $m3u8 = get($url,null,$headers)[0];
		$m3u8s = explode("\n",trim($m3u8));
		$d = '';
		foreach($m3u8s as $m3u8l){
			if (strpos($m3u8l,'ts')!==false){
				$d .= $urlp.urlencode($urlpath.$m3u8l)."&hostip={$hostip}&mode={$mode}".PHP_EOL;
			} else {
				$d .= $m3u8l.PHP_EOL;
			}
		}
		header("Content-Type: application/vnd.apple.mpegurl");
		header("Content-Disposition: inline; filename=index.m3u8");
	} else {
		$channel_id = $_GET['channel-id']??'ystenlive';
		$Contentid = $_GET['Contentid']??'8785669936177902664';
		$stbId = $_GET['stbId']??'toShengfen';
		$playseek = $_GET['playseek']??'';
		if ($playseek) {
			$t_arr = str_split(str_replace('-','.0',$playseek).'.0',8);
			$starttime = $t_arr[0].'T'.$t_arr[1].'0Z';
			$endtime = $t_arr[2].'T'.$t_arr[3].'0Z';
			$url1 = "http://gslbserv.itv.cmvideo.cn/index.m3u8?channel-id={$channel_id}&Contentid={$Contentid}&livemode=4&stbId={$stbId}&starttime={$starttime}&endtime={$endtime}";
		} else {
			$url1 = "http://gslbserv.itv.cmvideo.cn/index.m3u8?channel-id={$channel_id}&Contentid={$Contentid}&livemode=1&stbId={$stbId}";
		}
		$url2 = get($url1,1)[0];
		switch ($channel_id) {
			case 'bestzb':
				$channel_id = 'bestlive';
				break;
			case 'wasusyt':
				$channel_id = 'wasulive';
				break;
			case 'FifastbLive':
				$channel_id = 'fifalive';
				break;
			default:
				
				break;
		}
		if (strpos($url2,'cache.ott')===false){
			$position = strpos($url2,'/',8);
			$str = substr($url2,$position);
			$url2 = "http://cache.ott.{$channel_id}.itv.cmvideo.cn{$str}";
		}
		if ($mode==3){
			$url4 = $url2;
		} else {
			$url3 = urlencode($url2);
			$domain = "base-v4v6-cm-miguvideo.e.cdn.chinamobile.com";
			$ipsAArray = dns_get_record($domain, DNS_A);
			$hostip = $ipsAArray[rand(0, count($ipsAArray) - 1)]['ip'];
			$url4 = "{$https}://{$http_host}{$Uripath}?u={$url3}&hostip={$hostip}&mode={$mode}";
		}
		header("location:$url4");
		exit;
	}
}
print_r($d);
exit;

function get($url,$tran=0,$headers=["User-Agent: okhttp/5.0.0"]) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
	if($tran){
		curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_exec($ch);
		$data[0] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	} else {
		$data[0] = curl_exec($ch);
	}
	$data[1] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	return $data;
}

function getts($url, $headers) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
	curl_exec($ch);
	curl_close($ch);
}
?>

补

ref(APA): wheiss.Wheiss&#039; Blog.https://www.wheiss.com. Retrieved 2024/12/25.
