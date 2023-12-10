<?php
// 请求地址
$url = 'https://webtvapi.now.com/10/7/getLiveURL';
// 请求头信息
$headers = array(
    'Content-Type: application/json',
    'User-Agent: NNC/6.3.0 (com.now.news; build:2309121224; iOS 17.1.0) Alamofire/5.2.2'
);
// 请求体
$body = json_encode(array(
    'deviceType' => 'IOS_PHONE',
    'contentId' => '332',
    'audioCode' => 'A',
    'deviceId' => '8269809F-7702-45CE-9378-D7157A2E6819',
    'mode' => 'prod',
    'callerReferenceNo' => '20140702122500',
    'contentType' => 'Channel'
));
// 发送请求并获取响应数据
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$response = curl_exec($ch);
curl_close($ch);
// 解析响应体
$responseData = json_decode($response, true);
$assetUrl = $responseData['asset'][0];

// 获取直播流的基础 URL
$baseUrl = preg_replace('/\/07\.m3u8.*/', '', $assetUrl);

// 获取.m3u8文件的内容
$m3u8Content = file_get_contents($assetUrl);

// 提取直播流URL的前半部分
$baseUrl = preg_replace('/\/07\.m3u8.*/', '', $assetUrl);
$baseUrl .= '/';

// 替换.ts文件的URL为完整的直播流URL
$playUrl = preg_replace('|(.*?).ts|', $baseUrl . '$1.ts', $m3u8Content);

// 设置响应头的Content-Type为"application/x-mpegURL"
header('Content-Type: application/x-mpegURL');
echo $playUrl;
?>
