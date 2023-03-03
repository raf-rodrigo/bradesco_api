<?php

require 'gerarJwt.php';
require 'gerarAccessToken.php';
require 'configuracao.php';

$agencia = AGENCIA;
$tipoEntrada = TIPOENTRADA;
$dadosEntrada = DADOSENTRADA;
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