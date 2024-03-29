<?php

define('PCHECK',0);//密码检测开关 0为关闭 1为开启
define('P','123456admin');//密码内容

define('UACHECK',0);//UA检测开关 0为关闭 1为开启
define('UA','MTV');//UA设置内容

$ch['示例'] = ['id' => 'https://youtube.com/watch?v=后面的id','qn' => '清晰度 0 144P  1 240P 2 360P 3 480P 4 720P 5 1080P','cache' => '缓存模式开关 0为关闭 1为开启','files' => '允许缓存的文件个数','name' => '在列表显示时的名字'];
$ch['yscctvzhongwenguoji'] = ['id' => 'bEZYrBMYNNg','qn' => '4','cache' => '0','files' => '1000','name' => 'CCTV中文国际'];
$ch['twdaai'] = ['id' => 'MIqUplvSRWA','qn' => '4','cache' => '0','files' => '1000','name' => '大爱'];
$ch['twdaai2'] = ['id' => 'DTNkEm6jaqQ','qn' => '4','cache' => '0','files' => '1000','name' => 'DaAi2'];
$ch['twdongsencaijinggushi'] = ['id' => 'LbS-xQ67fos','qn' => '4','cache' => '0','files' => '1000','name' => '东森财经股市'];
$ch['twdongsencaijingxinwen'] = ['id' => 'WHEPzbFA3hw','qn' => '4','cache' => '0','files' => '1000','name' => '东森财经新闻'];
$ch['twdongsenlive'] = ['id' => '2dfAzcDf8Zg','qn' => '4','cache' => '0','files' => '1000','name' => '东森LIVE'];
$ch['tweftongyun'] = ['id' => 'KrORwDaq0Eo','qn' => '4','cache' => '0','files' => '1000','name' => 'EF通运'];
$ch['hkfenghuangzixun'] = ['id' => 'ALvxv4ZwK9c','qn' => '4','cache' => '0','files' => '1000','name' => '凤凰资讯'];
$ch['twguohuipindao1'] = ['id' => '4HysYHJ6GkY','qn' => '4','cache' => '0','files' => '1000','name' => '国会频道1'];
$ch['twguohuipindao2'] = ['id' => 'Lj8OWF6nzcg','qn' => '4','cache' => '0','files' => '1000','name' => '国会频道2'];
$ch['twhuashixinwenzixun'] = ['id' => 'wM0g8EoUZ_E','qn' => '4','cache' => '0','files' => '1000','name' => '华视新闻资讯'];
$ch['twhuanyuxinwentai'] = ['id' => '6IquAgfvYmc','qn' => '4','cache' => '0','files' => '1000','name' => '寰宇新闻台'];
$ch['twjingxinwen'] = ['id' => '5n0y6b0Q25o','qn' => '4','cache' => '0','files' => '1000','name' => '镜新闻'];
$ch['twmeihao1'] = ['id' => '7IyxOiGMLgw','qn' => '4','cache' => '0','files' => '1000','name' => '美好1'];
$ch['twminshixinwentai'] = ['id' => 'ylYJSBUgaMA','qn' => '4','cache' => '0','files' => '1000','name' => '民视新闻台'];
$ch['twsanliinews'] = ['id' => 'CKjSm5ZeehE','qn' => '4','cache' => '0','files' => '1000','name' => '三立iNEWS'];
$ch['twsanlilive'] = ['id' => 'FoBfXvlOR6I','qn' => '4','cache' => '0','files' => '1000','name' => '三立LIVE'];
$ch['twsanlilivej'] = ['id' => 'oZdzzvxTfUY','qn' => '4','cache' => '0','files' => '1000','name' => '三立LIVE+'];
$ch['twtaiwanj'] = ['id' => 'ovzTfk5CmIE','qn' => '4','cache' => '0','files' => '1000','name' => 'TAIWAN+'];
$ch['twtvbs'] = ['id' => 'YvdcZ_jpLXE','qn' => '4','cache' => '0','files' => '1000','name' => 'TVBS'];
$ch['twtvbslive'] = ['id' => 'm_dhMSvUCIc','qn' => '4','cache' => '0','files' => '1000','name' => 'TVBS LIVE'];
$ch['twtvbsxinwentai'] = ['id' => '2mCSYvcfhtc','qn' => '4','cache' => '0','files' => '1000','name' => 'TVBS新闻台'];
$ch['twtaishixinwen'] = ['id' => 'xL0ch83RAK8','qn' => '4','cache' => '0','files' => '1000','name' => '台视新闻'];
$ch['twxintangrenyataitai'] = ['id' => 'WIhgU_mc05A','qn' => '4','cache' => '0','files' => '1000','name' => '新唐人亚太台'];
$ch['twzhongshixinwen'] = ['id' => 'TCnaIE_SAtM','qn' => '4','cache' => '0','files' => '1000','name' => '中视新闻'];
$ch['twzhongtianxinwen'] = ['id' => 'oIgbl7t0S_w','qn' => '4','cache' => '0','files' => '1000','name' => '中天新闻'];
$ch['twzhongtianxinwen2'] = ['id' => 'WPfPjbOLNfE','qn' => '4','cache' => '0','files' => '1000','name' => '中天新闻2'];
$ch['twzhongtianyazhoutai'] = ['id' => 's2waUruqze4','qn' => '4','cache' => '0','files' => '1000','name' => '中天亚洲台'];




/**********
 +代码编写GeJI 恩山论坛
 +听说倒卖代码的人的妈死了
 +以下代码不懂不可作修改！！！
**********/
$id = $_GET['id'];
$ts = $_GET['ts'];
$p = $_GET['p'];
if(PCHECK && $p != P) {
    header('HTTP/1.1 405');
    echo '405 Not Allowed';
    exit();
};
if(!$ch[$id] && !$_GET['api']) {
    header('HTTP/1.1 404');
    echo '404 Not Found';
    exit();
};
if(UACHECK && $_SERVER['HTTP_USER_AGENT'] != UA) {
    header('HTTP/1.1 403');
    echo '403 Forbidden';
    exit();
};
if($_GET['api']) {
    foreach($ch as $a => $b) {
        echo $ch[$a]['name'].',http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?id='.$a.'&p='.$_GET['p'].PHP_EOL;
    };
    exit();
};
$data = file_get_contents('https://www.youtube.com/watch?v='.$ch[$id]['id']);
preg_match('/hlsManifestUrl":"(.*?)"/',$data,$m3u8url);
$data = file_get_contents($m3u8url[1]);
preg_match_all('/http(.*?)\n/',$data,$m3u8url);
$m3u8data = file_get_contents($m3u8url[0][$ch[$id]['qn']]);
if(!$ts) {
    header('content-type:application/x-mpegURL');
    $r = urldecode(preg_replace('/http(.*?)sq\//',basename(__FILE__).'?id='.$_GET['id'].'&p='.$_GET['p'].'&ts='.date('Ymd_'),$m3u8data));
    $r = str_replace('/file/seg.ts','',$r);
    echo $r;
} else {
    header('content-type:video/mp2t');
    preg_match_all('/clen=(.*?);|lmt=(.*?)\/|dur\/(.*?)$|(.*?)\//',$ts,$ids);
    header('Content-Disposition: attachment; filename="'.$ids[4][0].'.ts"');
    if(!$ch[$id]['cache']) {
        preg_match_all('/http(.*?)sq\//',$m3u8data,$urlhead);
        $url = $urlhead[0][0].preg_replace('/(.*?)_/','',$ids[4][0]).'/goap/clen%3D'.$ids[1][2].'%3Blmt%3D'.$ids[2][3].'/govp/clen%3D'.$ids[1][5].'%3Blmt%3D'.$ids[2][6].'/dur/'.$ids[3][7].'/file/seg.ts';
        $data = file_get_contents($url);
        echo $data;
    } else {
        @mkdir('cache/'.$ch[$id]['id'],0777,true);
        if(array_flip(scandir('cache/'.$ch[$id]['id']))[$ids[4][0].'.ts']) {
            header('location:cache/'.$ch[$id]['id'].'/'.$ids[4][0].'.ts');
        } else {
            preg_match_all('/http(.*?)sq\//',$m3u8data,$urlhead);
            $url = $urlhead[0][0].preg_replace('/(.*?)_/','',$ids[4][0]).'/goap/clen%3D'.$ids[1][2].'%3Blmt%3D'.$ids[2][3].'/govp/clen%3D'.$ids[1][5].'%3Blmt%3D'.$ids[2][6].'/dur/'.$ids[3][7].'/file/seg.ts';
            $data = file_get_contents($url);
            file_put_contents('cache/'.$ch[$id]['id'].'/'.$ids[4][0].'.ts',$data);
            header('location:cache/'.$ch[$id]['id'].'/'.$ids[4][0].'.ts');
        };
    };
};
$list = @scandir('cache/'.$ch[$id]['id']);
if (count($list) - 2 >= $ch[$id]['files']) {
    for ($i = 2; $i <= count($list) - 1; $i++) {
        unlink('cache/'.$ch[$id]['id'].'/'.$list[$i]);
    };
};