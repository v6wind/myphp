<?php

/*

*GEJI 恩山论坛

*KBS1高画质,.php?id=11&p=0

*KBS1高画质韩语字幕,.php?id=11&p=1

*KBS1低画质,.php?id=11&p=2

*KBS2高画质,.php?id=12&p=0

*KBS2高画质韩语字幕,.php?id=12&p=1

*KBS2低画质,.php?id=12&p=2

*KBS NEWS D,.php?id=81&p=0

*KBS drama,.php?id=N91&p=0

*KBS joy,.php?id=N92&p=0

*KBS story,.php?id=N94&p=0

*KBS LIFE,.php?id=N93&p=0

*KBS kids,.php?id=N96&p=0

*孤岛直播,.php?id=cctv01&p=0

*KBS WORLD,.php?id=14&p=0

*/

$id = $_GET['id'];

$p = $_GET['p'];

$data = json_decode(@file_get_contents('https://cfpwwwapi.kbs.co.kr/api/v1/landing/live/channel_code/'.$id));

header('location:'.$data->channel_item[$p]->service_url);

?>
