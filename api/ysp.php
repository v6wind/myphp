
<?php
// 报告所有错误，但忽略通知错误
error_reporting(E_ALL & ~E_NOTICE);

// 定义常量
define("PLATFORM", "5910204");
define("AUTH_SALT", "PUlOFD%XM9jEdvuR");
define("LIVE_SALT", '0f$IVHi9Qno?G');
define("KEY", hex2bin("48e5918a74ae21c972b90cce8af6c8be"));
define("IV", hex2bin("9a7e7d23610266b1d9fbf98581384d92"));
define("TICKET_KEY", "zaTIoAwiY366Kk*7");
define("TICKET_IV", "5HvQ8qPE7%dY3QGB");

// 主程序
$id = $_GET["id"] ?? "cctv1";
$channels = [
'cctv4k' => [2022575202, 600002264],
'cctv8k' => [2020603421, 600002259],
'cctv1' => [2022576802, 600001859],
'cctv2' => [2022576702, 600001800],
'cctv2b' => [2022576702, 600001800],
'cctv3' => [2022576502, 600001801],//vip
'cctv4' => [2022576602, 600001814],
'cctv5' => [2022576402, 600001818],
'cctv5p' => [2022576302, 600001817],
//'cctv6' => [2022574302, 600001802],//vip
'cctv6' => [2013693901, 600001802],//vip
'cctv7' => [2022576202, 600004092],
'cctv8' => [2022576102, 600001803],//vip
'cctv9' => [2022576002, 600004078],
'cctv10' => [2022573002, 600001805],
'cctv11' => [2022575902, 600001806],
'cctv12' => [2022575801, 600001807],
'cctv13' => [2022575702, 600001811],
'cctv14' => [2022575602, 600001809],
'cctv15' => [2022575502, 600001815],
'cctv16' => [2022575402, 600098637],
'cctv16-4k' => [2022575102, 600099502],//vip
'cctv17' => [2022575302, 600001810],
'bqkj' => [2012513402, 600099649],//从这里开始
'dyjc' => [2012514402, 600099655],
'hjjc' => [2012511202, 600099620],
'fyjc' => [2012513602, 600099658],
'fyyy' => [2012514102, 600099660],
'fyzq' => [2012514202, 600099636],
'dszn' => [2012514002, 600099656],
'nxss' => [2012513902, 600099650],
'whjp' => [2012513802, 600099653],
'sjdl' => [2012513302, 600099637],
'gefwq' => [2012512502, 600099659],
'ystq' => [2012513702, 600099652],
'wsjk' => [2012513502, 600099651],//到这里结束，都是vip
'cgtn' => [2022575002, 600014550],
'cgtnjl' => [2022574702, 600084781],
'cgtne' => [2022571703, 600084744],
'cgtnf' => [2022574902, 600084704],
'cgtna' => [2022574602, 600084782],
'cgtnr' => [2022574802, 600084758],
'cetv1' => [2022823801, 600001810],//CETV1
'bjws' => [2024055302, 600002309],
'dfws' => [2000292402, 600002483],
'tjws' => [2019927002, 600152137],//天津
'cqws' => [2000297802, 600002531],
'hljws' => [2000293902, 600002498],
'lnws' => [2000281302, 600002505],
'hbws' => [2000293402, 600002493],
'sdws' => [2000294802, 600002513],
'ahws' => [2000298002, 600002532],
'hnws' => [2000296102, 600002525],
'hubws' => [2000294502, 600002508],
'hunws' => [2000296202, 600002475],
'jxws' => [2000294102, 600002503],
'jsws' => [2000295602, 600002521],
'zjws' => [2000295502, 600002520],
'dnws' => [2000292502, 600002484],
'gdws' => [2000292702, 600002485],
'szws' => [2000292202, 600002481],
'gxws' => [2000294202, 600002509],
'gzws' => [2000293302, 600002490],
'scws' => [2000295002, 600002516],
'xjws' => [2019927402, 600152138],//新疆
'hinws' => [2000291502, 600002506],
'btws' => [2022606701, 600085259],//兵团卫视
];

$cnlid = $channels[$id][0];
$livepid = $channels[$id][1];

//拒绝无效id
$errorurl = "http://cdn.jdshipin.com:8880/404.html?ysp&{$id}";
//$errorurl = "https://volunteer.cdn-go.cn/404/latest/404.html";

if(intval(key_exists("$id",$channels)) === 0){
        header('Location: '.$errorurl);
        exit;
         }

//CCTV6、CETV1、兵团卫视特殊格式处理
if($id == 'cctv6'||$id == 'cetv1'||$id == 'btws') {
        $live = "https://mobilelive-ds.ysp.cctv.cn/ysp/{$cnlid}.m3u8";
        header('Location: '.$live);
        exit;
  } 

// 缓存文件路径
$cache_file = 'cache/ysp_live_cache_' . $id . '.txt';

// 检查缓存文件是否存在并且未过期
if (file_exists($cache_file) && time() - filemtime($cache_file) < 150) {
    // 如果缓存文件存在且未过期，直接从缓存文件中读取 $live
    $live = file_get_contents($cache_file);
} else {
    // 否则重新获取 $live 并保存到缓存文件中
$guid = "lvm8gvuy_" . generateRandomString(11);

// 获取当前毫秒级别时间
$currentTimeMillis = round(microtime(true) * 1000);
$request_id = "999999" . generateRandomString(10) . $currentTimeMillis;

$data = getLive($cnlid, $livepid, $guid, $request_id);
$json = json_decode($data);
$live = $json->data->playurl;
$extended_param = $json->data->extended_param;
$chanllCode = json_decode($json->data->chanll)->code;
$decodeChanll = base64_decode($chanllCode);

// 定义正则表达式来匹配des_key和des_iv的赋值语句
$patternKey = '/var des_key = "(.*?)";/';
$patternIv = '/var des_iv = "(.*?)";/';

// 初始化变量用于存储提取的值
$desKey = "";
$desIv = "";

// 使用正则表达式提取des_key的值
if (preg_match($patternKey, $decodeChanll, $matchesKey)) {
    $desKey = $matchesKey[1];
}

// 使用正则表达式提取des_iv的值
if (preg_match($patternIv, $decodeChanll, $matchesIv)) {
    $desIv = $matchesIv[1];
}
//定义待加密数组
$jsonString =
    '{"mver":"1","subver":"1.2","host":"www.yangshipin.cn/#/tv/home?pid=","referer":"","canvas":"YSPANGLE(Intel,Intel(R)Iris(R)XeGraphics(0x000046A6)Direct3D11vs_5_0ps_5_0,D3D11)"}';
$data = json_decode($jsonString, true);

//定义变量保存revoi值
$encryptedHex = encryptData($data, $desKey, $desIv);
$live = $live . "&revoi=" . $encryptedHex . $extended_param;

//替换cdn域名，国内服务器可删除此行代码
$live = str_replace("https://outlivecloud-cdn.ysp.cctv.cn", "http://hlslive-tx-cdn.ysp.cctv.cn", $live);

   // 将 $live 写入缓存文件
    file_put_contents($cache_file, $live);
}

preg_match('/^(.*\/)/', $live, $matches);
$burl = $matches[1];

// 获取并修改m3u8文件内容
$d = file_get_contents($live);
$str = preg_replace("/(.*?.ts)/", $burl . "$1", $d);

// 输出m3u8文件
header("Content-Type: application/vnd.apple.mpegurl");
header("Content-Disposition: inline; filename=index.m3u8");
echo $str;

// 生成随机字符串
function generateRandomString($length = 10)
{
    $characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $randomString = "";
    $charLength = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charLength - 1)];
    }
    return $randomString;
}

// 对数据进行签名
function signData($data, $salt)
{
    ksort($data);
    $signature = http_build_query($data) . $salt;
    return md5($signature);
}

// 生成输入参数的哈希值
function generateInput($data)
{
    uksort($data, "strcasecmp");
    return md5(http_build_query($data));
}

// 获取序列号
function getSequence()
{
    $filePath = "sequence.txt";
    $sequenceID = file_exists($filePath)
        ? (int) file_get_contents($filePath) + 1
        : 1;
    file_put_contents($filePath, $sequenceID);
    return $sequenceID;
}

// 获取播放令牌
function getToken($livepid, $guid, $request_id)
{
    $seq_id = getSequence();
    $param = [
        "pid" => $livepid,
        "guid" => $guid,
        "appid" => "ysp_pc",
        "rand_str" => generateRandomString(10),
    ];
    $signature = signData($param, AUTH_SALT);
    $param["signature"] = $signature;

    $authUrl = "https://player-api.yangshipin.cn/v1/player/auth";
    $headers = [
        "Content-Type: application/x-www-form-urlencoded;charset=UTF-8",
        "Referer: https://www.yangshipin.cn/",
        "Cookie: guid={$guid}; versionName=99.99.99; versionCode=999999; vplatform=109; platformVersion=Chrome; deviceModel=125; newLogin=1; updateProtocol=1; nseqId={$seq_id}; nrequest-id={$request_id}",
        "Yspappid: 519748109",
    ];
    $ch = curl_init($authUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
    $data = curl_exec($ch);
    curl_close($ch);
    $json_data = json_decode($data);
    return $json_data->data ?? null;
}

// 获取API令牌
function getApiToken($guid)
{
    $url = "https://h5access.yangshipin.cn/web/open/token?yspappid=519748109&guid=" . 
           $guid . 
           "&vappid=59306155&vsecret=b42702bf7309a179d102f3d51b1add2fda0bc7ada64cb801&raw=1&ts=" . 
           round(microtime(true) * 1000);

    // 初始化 cURL 会话
    $ch = curl_init();

    // 设置 cURL 选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Referer: https://www.yangshipin.cn/'
    ]);

    // 执行 cURL 请求并获取响应
    $response = curl_exec($ch);

    // 检查是否有错误
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
        return null;
    }

    // 关闭 cURL 会话
    curl_close($ch);

    // 解析 JSON 数据
    $json_data = json_decode($response);

    return $json_data->data->token ?? null;
}

// 获取直播信息
function getLive($cnlid, $livepid, $guid, $request_id)
{
    $seq_id = getSequence();
    $ts = time();
    $el =
        "|{$cnlid}|{$ts}|mg3c3b04ba|V1.0.0|{$guid}|" .
        PLATFORM .
        "|https://www.yangshipin.c|mozilla/5.0 (windows nt ||Mozilla|Netscape|Win32|";
    $len = strlen($el);
    $xl = 0;
    for ($i = 0; $i < $len; $i++) {
        $xl = ($xl << 5) - $xl + ord($el[$i]);
        $xl &= 0xffffffff;
    }
    $xl = $xl > 2147483648 ? $xl - 4294967296 : $xl;
    $el = "|" . $xl . $el;

    $ckey =
        "--01" .
        strtoupper(
            bin2hex(
                openssl_encrypt($el, "AES-128-CBC", KEY, OPENSSL_RAW_DATA, IV)
            )
        );

    $params = [
        "cnlid" => (string) $cnlid,
        "livepid" => (string) $livepid,
        "stream" => "2",
        "guid" => $guid,
        "cKey" => $ckey,
        "adjust" => 1,
        "sphttps" => "1",
        "platform" => PLATFORM,
        "cmd" => "2",
        "encryptVer" => "8.1",
        "dtype" => "1",
        "devid" => "devid",
        "otype" => "ojson",
        "appVer" => "V1.0.0",
        "app_version" => "V1.0.0",
        "channel" => "ysp_tx",
        "defn" => "fhd",
    ];

    $token = getToken($livepid, $guid, $request_id);
    if (!$token) {
        return null; // Handle token retrieval failure
    }

    $input = generateInput($params);

    $params["rand_str"] = generateRandomString(10);
    $params["signature"] = signData($params, LIVE_SALT);

    $apiToken = getApiToken($guid);
    if (!$apiToken) {
        return null; // Handle API token retrieval failure
    }

    $ticket = encrypt_aes_128_ctr(
        $livepid . "&" . $token->ts . "&" . $guid . "&519748109",
        TICKET_KEY,
        TICKET_IV
    );

    $info =
        "yspappid:519748109;host:www.yangshipin.cn;protocol:https:;token:" .
        $apiToken .
        ";input:" .
        $input .
        "-" .
        $guid .
        "-" .
        $seq_id .
        "-" .
        $request_id .
        ";";
    $sign = md5($info);

    $headers = [
        "Content-Type: application/json",
        "Referer: https://www.yangshipin.cn/",
        "Cookie: guid={$guid}; versionName=99.99.99; versionCode=999999; vplatform=109; platformVersion=Chrome; deviceModel=125; newLogin=1; updateProtocol=1; nseqId={$seq_id}; nrequest-id={$request_id}",
        "Yspappid: 519748109",
        "Request-Id: {$request_id}",
        "Seqid: {$seq_id}",
        "Yspplayertoken: {$token->token}",
        "Yspsdkinput: {$input}",
        "Yspsdksign: {$sign}",
        "Yspticket: {$ticket}",
    ];

    $bstrURL = "https://player-api.yangshipin.cn/v1/player/get_live_info";
    $ch = curl_init($bstrURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

// 加密数据
function encryptData($data, $desKey, $desIv)
{
    $plaintext = json_encode($data, JSON_UNESCAPED_SLASHES);
    $key = base64_decode($desKey);
    $iv = base64_decode($desIv);
    $encrypted = openssl_encrypt(
        $plaintext,
        "des-ede3-cbc",
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );
    return strtoupper(bin2hex($encrypted));
}

// 使用AES-128-CTR算法对数据进行加密
function encrypt_aes_128_ctr($data, $key, $iv)
{
    $encrypted = openssl_encrypt(
        $data,
        "aes-128-ctr",
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );
    if ($encrypted === false) {
        throw new RuntimeException(
            "Encryption failed: " . openssl_error_string()
        );
    }
    return bin2hex($encrypted);
}
?>
