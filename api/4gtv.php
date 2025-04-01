<?php

// 错误报告设置
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 获取频道ID参数
$id = $_GET['id'] ?? "1";

// 频道ID到ASSET_ID的映射表
$idtofsASSETID = [
    '1' => '4gtv-4gtv003',
    '2' => '4gtv-4gtv001',
    // ... (保持原有的完整映射表) ...
    '293' => '4gtv-4gtv153',
];

// 检查ID是否有效
if (!isset($idtofsASSETID[$id])) {
    http_response_code(404);
    header('Content-Type: application/json');
    die(json_encode(['error' => '频道ID不存在']));
}

$fsASSET_ID = $idtofsASSETID[$id];
$authval = generate4GTV_AUTH();
$fsENC_KEY = generateUuid();

// 初始化cURL请求
$curl = curl_init();

$postData = [
    'fnCHANNEL_ID' => $id,
    'fsDEVICE_TYPE' => 'mobile',
    'clsAPP_IDENTITY_VALIDATE_ARUS' => [
        'fsVALUE' => '',
        'fsENC_KEY' => $fsENC_KEY
    ],
    'fsASSET_ID' => $fsASSET_ID
];

curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api2.4gtv.tv/App/GetChannelUrl2',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($postData),
    CURLOPT_HTTPHEADER => [
        "4GTV_AUTH: $authval",
        'fsDEVICE: iOS',
        'fsVALUE: ',
        'fsVERSION: 3.2.1',
        "fsENC_KEY: $fsENC_KEY",
        'User-Agent: %E5%9B%9B%E5%AD%A3%E7%B7%9A%E4%B8%8A/1 CFNetwork/1568.200.51 Darwin/24.1.0',
        'Content-Type: application/json',
        'Accept: */*',
        'Connection: keep-alive'
    ],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT => 10
]);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$error = curl_error($curl);
curl_close($curl);

// 处理API响应
if ($error || !$response) {
    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode(['error' => 'API请求失败', 'details' => $error]));
}

$data = json_decode($response, true);

// 验证响应数据
if (!isset($data['Data']['flstURLs']) || !is_array($data['Data']['flstURLs'])) {
    http_response_code(502);
    header('Content-Type: application/json');
    die(json_encode(['error' => '无效的API响应']));
}

// 筛选URL
$urls = $data['Data']['flstURLs'];
$finalUrl = '';
foreach ($urls as $url) {
    if (strpos($url, 'cds.cdn.hinet.net') === false) {
        $finalUrl = $url;
        break;
    }
}

if (empty($finalUrl)) {
    http_response_code(404);
    header('Content-Type: application/json');
    die(json_encode(['error' => '未找到可用的流媒体URL']));
}

// 处理4gtvfree URL
if (strpos($finalUrl, 'https://4gtvfree-mozai.4gtv.tv') === 0) {
    $finalUrl = str_replace('/index.m3u8?', '/1080.m3u8?', $finalUrl);
    header('Location: ' . $finalUrl);
    exit();
}

// 处理其他URL
try {
    $finalUrl = get_playURL($finalUrl, 'url');
    if (!$finalUrl) {
        throw new Exception('无法获取播放URL');
    }

    $m3u8Content = get_playURL($finalUrl, "ts");
    if (!$m3u8Content) {
        throw new Exception('无法获取M3U8内容');
    }

    $preArray = explode('/', explode('?', $finalUrl)[0]);
    if (count($preArray) < 1) {
        throw new Exception('解析URL失败');
    }

    $midArray = explode('-', end($preArray));
    if (count($midArray) < 2) {
        throw new Exception('解析频道信息失败');
    }

    $channel = $midArray[0] . '-' . $midArray[1];
    $prex = "https://litvpc-hichannel.cdn.hinet.net/live/pool/{$channel}/litv-pc/";

    $lines = [];
    foreach (explode("\n", $m3u8Content) as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        if (strpos($line, '#EXT') === 0) {
            $lines[] = $line;
        } else {
            $ts_file_array = explode('/', explode('?', $line)[0]);
            $ts_file = end($ts_file_array);
            $ts_file = str_replace(['video=2000000', 'video=2936000', 'video=3000000'], 'video=6000000', $ts_file);
            $ts_file = str_replace(['avc1_2000000=3', 'avc1_2000000=6', 'avc1_2936000=4', 'avc1_3000000=3'], 'avc1_6000000=1', $ts_file);
            $ts_url = $prex . $ts_file;
            $lines[] = $ts_url;
        }
    }

    header("Content-Type: application/vnd.apple.mpegurl");
    echo implode("\n", $lines);
    exit();

} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode(['error' => $e->getMessage()]));
}

// 生成UUID函数
function generateUuid() {
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return strtoupper(vsprintf('%08s-%04s-%04s-%04s-%12s', str_split(bin2hex($data), 4)));
}

// 生成4GTV认证令牌函数
function generate4GTV_AUTH() {
    $headKey = "PyPJU25iI2IQCMWq7kblwh9sGCypqsxMp4sKjJo95SK43h08ff+j1nbWliTySSB+N67BnXrYv9DfwK+ue5wWkg==";
    $KEY = "ilyB29ZdruuQjC45JhBBR7o2Z8WJ26Vg";
    $IV = "JUMxvVMmszqUTeKn";
    $decode = base64_decode($headKey);
    $format = gmdate("Ymd");
    $decrypted = openssl_decrypt($decode, "aes-256-cbc", $KEY, OPENSSL_RAW_DATA, $IV);
    $toHash = $format . $decrypted;
    $sha512Binary = hash('sha512', $toHash, true);
    return base64_encode($sha512Binary);
}

// 获取播放URL函数
function get_playURL($url, $return_type) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => ['User-Agent: okhttp/3.12.11'],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 5
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error || !$response) {
        return null;
    }

    $response = trim($response);
    if ($return_type === 'ts') {
        return $response;
    }

    $lines = explode("\n", $response);
    $latest_line = end($lines);
    if (empty($latest_line)) {
        return null;
    }

    $parsed_url = parse_url($url);
    $url_path = dirname($parsed_url['path'] ?? '');
    return sprintf('%s://%s%s/%s',
        $parsed_url['scheme'] ?? 'https',
        $parsed_url['host'] ?? '',
        $url_path,
        $latest_line
    );
}
