<?php
$access_token = 'U5BKa4Ux7/5j8a6uoQBELYHT5DAgXJb9IJ5jyUc3sWSm62AwsgzDZ5oLSbOVW6XWEmZG0D1Vn/IM24s64N/4hKXZ5pKsL/Ff5e/8YZ1FovYynqrEC/qzGoSKUSGHs0ir1AvwGDqiUO40iyvZRuvNsgdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;