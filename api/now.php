<?php
$url = 'https://webtvapi.now.com/10/7/getLiveURL';
$headers = array(
    'Content-Type: application/json',
    'User-Agent: NNC/6.3.0 (com.now.news; build:2309121224; iOS 17.1.0) Alamofire/5.2.2'
);
$body = json_encode(array(
    'deviceType' => 'IOS_PHONE',
    'contentId' => '332',
    'audioCode' => 'A',
    'deviceId' => '8269809F-7702-45CE-9378-D7157A2E6819',
    'mode' => 'prod',
    'callerReferenceNo' => '20140702122500',
    'contentType' => 'Channel'
));
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
$responseData = json_decode($response, true);
$assetUrl = $responseData['asset'][0];
$baseUrl = preg_replace('/\/[^\/]+$/', '', $assetUrl);
$m3u8Content = file_get_contents($assetUrl);
$lines = explode("\n", $m3u8Content);
$playlists = array();
foreach ($lines as $line) {
    if (strpos($line, '.m3u8') !== false) {
        $playlistUrl = $baseUrl . '/' . trim($line);
        $playlistContent = file_get_contents($playlistUrl);
        $playlistLines = explode("\n", $playlistContent);
        $playlistBaseUrl = preg_replace('/\/[^\/]+$/', '', $playlistUrl);
        $playlistUrls = array();
        foreach ($playlistLines as $playlistLine) {
            if (strpos($playlistLine, '.ts') !== false) {
                $playlistUrls[] = $playlistBaseUrl . '/' . trim($playlistLine);
            }
        }
        $playlists[] = implode("\n", $playlistUrls);
    }
}
header('Content-Type: application/x-mpegurl');
echo implode("\n", $playlists);
?>
