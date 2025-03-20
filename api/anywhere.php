<?php

// 获取 URL 参数中的 ch 值，默认为 1
$channelId = isset($_GET['ch']) ? intval($_GET['ch']) : 1;

// 验证 live_channel_id 是否为有效值
if ($channelId <= 0) {
    echo "Invalid channel ID provided.";
    exit;
}

// 设置 CURL 请求的 URL 和头信息
$url = 'https://uapisfm.tvbanywhere.com/video/channel/checkout';
$headers = [
    'accept: application/json, text/plain, */*',
    'accept-language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
    'authorization:eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpYXQiOjE3NDI0NTc2NTksImV4cCI6MTc0MjU0MDQ1OSwibmJmIjoxNzQyNDU3NjU5LCJkZXZpY2VfaWQiOiIyMjA1NDk0NzciLCJkZXZpY2Vfb3MiOiJ3ZWIiLCJkZXZpY2Vfcm9sZSI6bnVsbCwicGxhdGZvcm0iOiJhcHAiLCJkZXZpY2VfbGFuZ3VhZ2UiOiJoayIsImRybV9pZCI6IiIsImFwcF90eXBlIjoic2ciLCJsaWZldGltZV9pZCI6IndlYi11bmtub3duIiwiZGV2aWNlX3R5cGUiOiJwcm9kIiwidHZiX2FjY291bnRfaWQiOiI1MjczMTE5IiwidXNlcl9pZCI6IjEzNzU5MTkiLCJ1c2VyX25pY2tuYW1lIjoidjZ3aW5kIiwidXNlcl90aHVtYm5haWxfaW1hZ2UiOiIiLCJ1c2VyX2JhY2tncm91bmRfaW1hZ2UiOiIiLCJ1c2VyX2xldmVsIjoiIiwidXNlcl9iYWRnZSI6IiIsIm1fdG9rZW4iOiIiLCJvdmVycmlkZV9jb3VudHJ5X2NvZGUiOiIifQ.L-hwZQdqaJB5Su5-cmTLpddDOC46GCArLkqdDSCgR3Y',
    'content-type: application/json;charset=UTF-8',
    'origin: https://www.tvbanywhere.com',
    'referer: https://www.tvbanywhere.com/',
    'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0'
];
$data = json_encode([
    'live_channel_id' => $channelId,
    'platform' => 'webtv',
    'country' => 'JP',
    'start_time' => time(),
    'stop_time' => null,
    'quality' => 'auto',
    'broadcast' => 'webtv'
]);

// 初始化 CURL
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

// 执行 CURL 请求并获取响应
$response = curl_exec($curl);

// 检查 CURL 错误
if (curl_errno($curl)) {
    echo 'CURL Error: ' . curl_error($curl);
    curl_close($curl);
    exit;
}

curl_close($curl);

// 直接打印原始 JSON 响应
header('Content-Type: application/json');
echo $response;

?>
