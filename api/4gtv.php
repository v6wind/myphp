<?php

// ... 其他代码

// 执行 cURL 请求
$response = curl_exec($curl);

// 错误处理：检查 cURL 是否成功
if (curl_errno($curl)) {
    echo 'Error: cURL error: ' . curl_error($curl);
    curl_close($curl);
    exit;
}

// 关闭 cURL
curl_close($curl);

// 调试：打印原始响应
echo "Response: " . htmlspecialchars($response) . "<br>";

// 检测编码格式
$encoding = mb_detect_encoding($response);
echo "Encoding: " . $encoding . "<br>";

// 转换编码格式
if ($encoding != 'UTF-8') {
    $response = mb_convert_encoding($response, 'UTF-8', $encoding);
}

// 解析 JSON 响应
$data = json_decode($response, true);

// 错误处理：检查 JSON 解析是否成功
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    echo 'Error: JSON decode error: ' . json_last_error_msg();
    exit;
}

// ... 其他代码
