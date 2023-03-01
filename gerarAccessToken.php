<?php

require 'gerarJwt.php';

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://proxy.api.prebanco.com.br/auth/server/v1.1/token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'grant_type=urn%3Aietf%3Aparams%3Aoauth%3Agrant-type%3Ajwt-bearer&assertion='. $jwt,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = json_decode(curl_exec($curl));

curl_close($curl);

// print_r($response);

$accessToken = $response->access_token;

include_once ("gerarRequestValidarBoleto.php");