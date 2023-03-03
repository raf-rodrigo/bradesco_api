<?php

$request = file_get_contents("requestEfetuaPagamento.txt");

$KeyPrivate = file_get_contents("casadocreditoprivado.pem");

openssl_sign($request, $signature, $KeyPrivate, 'sha256');

$encodedSignature = base64_encode($signature);

$cleanSignature = str_replace(["\n", "\r", " ", "="], "", $encodedSignature);
$efetuaPagamentoSignature = str_replace(["+", "/"], ["-", "_"], $cleanSignature);

// echo $boletoSignature;