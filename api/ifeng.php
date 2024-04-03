<?php
date_default_timezone_set("Asia/Shanghai");
$id = $_GET['id'] ?? 'zx';
$n = [
    'ws'=>'0701pcc72',//凤凰卫视
    'zx'=>'0701pin72',//凤凰资讯
    'hk'=>'0701phk72',//凤凰香港
];

if (strstr($id, '4')) {
    $seq = intval(time() / 3.029 + 1134263867);
} elseif (strstr($id, '5')) {
    $seq = intval(time() / 3.026 + 1130361490);
} elseif (strstr($id, '6')) {
    $seq = intval(time() / 3.008 + 1130361113);
} else {
    $seq = intval(time() / 4.000 + 1269967460)-3;       //-3以实际测试调整
}

$content = "#EXTM3U\n#EXT-X-VERSION:3\n#EXT-X-TARGETDURATION:4\n#EXT-X-MEDIA-SEQUENCE:$seq\n";
for($i=0;$i<3;$i++){
    $content .= "#EXTINF:4.000,\n";
    $content .= "http://qctv.fengshows.cn/live/".$n[$id]."-".strval($seq+$i).".ts\n";
}
header("Content-Type: application/vnd.apple.mpegurl");
header("Content-Disposition: attachment; filename=playlist.m3u8");
echo $content;
