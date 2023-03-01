<?php

require 'gerarJwt.php';
require 'gerarAccessToken.php';

$agencia = 3963;
$tipoEntrada = 1;
$dadosEntrada = "00192923900000010000000001732554881424617717";
$inJson = array(
  "agencia"=>$agencia,
  "tipoEntrada"=>$tipoEntrada,
  "dadosEntrada"=>"$dadosEntrada"
);
$bodyJson = json_encode($inJson);

$nameFile = "requestBoleto.txt";

$content = "POST\n/oapi/v1/pagamentos/boleto/validarDadosTitulo\n\n$bodyJson\n$accessToken\n$jti\n$xBradTimestamp\nSHA256";

$file = fopen($nameFile, 'w');

fwrite($file, $content);

fclose($content);

include_once("signatureValidaBoleto.php");