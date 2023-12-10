<?php
$id=$_GET["id"];
$bstrURL = 'https://d1jithvltpp1l1.cloudfront.net/getLiveURL?channelno='.$id.'&format=HLS';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $bstrURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0" );
$data = curl_exec($ch);
curl_close($ch);
$reArr = json_decode($data,true);
header('location:'.$reArr["asset"]["hls"]["adaptive"][0]);
