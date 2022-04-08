<?php
$account_id = '';

$url = "https://api.cloudflare.com/client/v4/accounts/$account_id/registrar/domains";

$curl = curl_init();

$headers = [
    'X-Auth-Email: example@gmail.com',
    'X-Auth-Key: your_global_api_key',
    'Content-Type: application/json'
];

$body = '{"data":value}';

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

$data = curl_exec($curl);

curl_close($curl);
