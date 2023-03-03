<?php

require 'gerarJwt.php';
require 'gerarAccessToken.php';
require 'gerarRequestEfetuaPagamento.php';
require 'signatureEfetuaPagamento.php';

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://proxy.api.prebanco.com.br/oapi/v1/pagamentos/boleto/efetivarPagamento',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $bodyJson,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Accept: application/json',
    'Access-token: 8f99b0e0-802b-46a1-b37b-bc3c19563af6',
    'Authorization: Bearer '.$accessToken,
    'X-Brad-Signature: ' .$$efetuaPagamentoSignature,
    'X-Brad-Nonce: ' .$jti,
    'X-Brad-Timestamp: '.$xBradTimestamp,
    'X-Brad-Algorithm: SHA256'
  ),
));

$response = json_decode(curl_exec($curl));

curl_close($curl);

print_r($response);