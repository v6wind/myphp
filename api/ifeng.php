    <?php
    $id = $_GET['id'] ?? '3'; // 1=凤凰资讯 2=凤凰中文 3=凤凰HK
    $type = $_GET['qa'] ?? ''; //缺省=360p HD=480p FHD=720p audio=纯音频（纯音频未处理，若持久再更新）
    $id = $id - 1;
    $idUrl = 'https://m.fengshows.com/api/v3/live?live_type=tv';
    $authUrl = 'https://m.fengshows.com/api/v3/hub/live/auth-url?stream_type=hls&live_id=';

    $headers = array(
        'Fengshows-Client: app(ios,5040873);iPhone14,5;16.6',
        'Cookie: acw_tc=0bc159c416601310655302310e60a16525c75b28a289968d7981e08cf77999',
        'User-Agent: FengWatch/5.4.8 (com.phoenixtv.videoapp; build:5040873; iOS 16.6.0) Alamofire/5.6.4'
    );

    $liveId = json_decode(get_data($idUrl, $headers), true)[$id]['_id'];
    $rawUrl = json_decode(get_data($authUrl . $liveId . '&live_qa='.$type, $headers),true)['data']['live_url'];
    $playUrl = get_data($rawUrl,$headers);
    $serverUrl = "http://qctv.fengshows.cn/live/";
    $fixedPlayUrl = preg_replace('/0701(\w{3})(\d{2})(-\d+\.ts)\?.*/', $serverUrl.'0701${1}72$3', $playUrl);

    header("Content-Type: application/vnd.apple.mpegURL");
    header("Content-Disposition: attachment; filename=playlist.m3u8");
    echo $fixedPlayUrl;

    function get_data($url, $headers, $payload = null)
    {
        $curl = curl_init();
        if (str_starts_with($url, 'https')) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);

        if (!is_null($payload)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        }

        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            echo "CURL error: " . curl_error($curl);
            return null;
        }
        curl_close($curl);
        return $data;
    }
