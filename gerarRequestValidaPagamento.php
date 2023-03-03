<?php

require 'gerarJwt.php';
require 'gerarAccessToken.php';
require 'consultaDadosBoleto.php';
require 'configuracao.php';

//obrigatórios
$agencia = AGENCIA; //number - código da agência de Débito 
$bancoContaDebitada = BANCOCONTADEBITADA; // number - código do banco da conta debitada 
$agenciaContaDebitada = AGENCIACONTADEBITADA; //number - código da agência da conta debitada
$digitoAgenciaContaDebitada = DIGITOAGENCIADEBITADA;//number - dígito da conta debitada
$contaDebitada = CONTADEBITADA;//number - código da conta debitada
$digitoContaDebitada = DIGITOCONTADEBITADA;// number - dígito da conta debitada
$dataMovimento = DATAMOVIMENTO;//number - data do movimento no formato AAAAMMDD
$dataPagamento = DATAPAGAMENTO; //number - data de pagamento no formato AAAAMMDD
$horaTransacao = $horarioTransacao; //number - hora da transação no formato HHMMSS
$identificacaoTituloCobranca = DADOSENTRADA; //number - código de barras
$indicadorFormaCaptura = 1; // number - indentificador de captura - Fixo 1 - código de barras
$identificacaoChequeCartao = 0; //number - identificação do cheque ou cartão - Fixo "0"
$indicadorValidacaoGravacao = "N"; //alfa - indicador de validação e gravação - Fixo "N"
if ($response->consultaFatorDataVencimentoResponse->bancoTitulo == 237)
{
    $dataVencimento = $response->consultaFatorDataVencimentoResponse->valorTitulo; // number - data de vencimento no formato AAAAMMDD
    $valor = $response->consultaFatorDataVencimentoResponse->valorTitulo; //number - valor do título (corresponde ao valor pelo qual o cliente quitará o título)
    $numeroControleParticipante = 0;//alfa - dever se enviado para pagamento de título de outros bancos. Nesse caso enviar o conteúdo retornado no campo de mesmo nome da saída do passo 1. Para pagamentos de títulos bradesco ENVIAR "0"
}else
{
    $dataVencimento = $response->consultaDadosTituloCIPResponse->dataVencimento; // number - data de vencimento no formato AAAAMMDD
    $valor = $response->consultaDadosTituloCIPResponse->valorTitulo;
    $numeroControleParticipante = $response->consultaFatorDataVencimentoResponse->numeroControleParticipante;
}


//não obrigatórios
$nomeCliente; //alfa - nome do cliente
$valorMinimoIdentificacao = 0;//number - valor mínimo de identificação - Fixo "0"
$dadosSegundaLinhaExtrato;//alfa - histórico complementar / 2ªlinha de extrato
$cpfCnpjRemetente; //number - código do CPF/CNPJ do remetente do boleto
$cpfCnpjPortador;//number - código do CPF/CNPJ do portador do boleto
$cpfCnpjDestinatario;//number - código do CPF/CNPJ do destinatário do título


$inJson = array(
    "agencia"=>$agencia,
    "pagamentoComumRequest"=>array(
        "contaDadosComum"=>array(
        "agenciaContaDebitada"=>$agenciaContaDebitada,
        "bancoContaDebitada"=>$bancoContaDebitada,
        "contaDebitada"=>$contaDebitada,
        "digitoAgenciaDebitada"=>$digitoAgenciaContaDebitada,
        "digitoContaDebitada"=>$digitoContaDebitada
        ),
    "dadosSegundaLinhaExtrato"=>$dadosSegundaLinhaExtrato,
    "dataMovimento"=>$dataMovimento,
    "dataPagamento"=>$dataPagamento,
    "dataVencimento"=>$dataVencimento,
    "horaTransacao"=>$horaTransacao,
    "identificacaoTituloCobranca"=>$identificacaoTituloCobranca,
    "indicadorFormaCaptura"=>$indicadorFormaCaptura,
    "valorTitulo"=>$valor
    ),
    "destinatarioDadosComum"=>array(
        "cpfCnpjDestinatario"=>$cpfCnpjDestinatario
    ),
    "identificacaoChequeCartao"=>$identificacaoChequeCartao,
    "indicadorValidacaoGravacao"=>$indicadorValidacaoGravacao,
    "nomeCliente"=>$nomeCliente,
    "numeroControleParticipante"=>$numeroControleParticipante,
    "portadorDadosComum"=>array(
        "cpfCnpjPortador"=>$cpfCnpjPortador
    ),
    "remetenteDadosComum"=>array(
        "cpfCnpjRemetente"=>$cpfCnpjRemetente
    ),
    "valorMinimoIdentificacao"=>$valorMinimoIdentificacao
);

$bodyJson = json_encode($inJson);

$nameFile = "requestPagamento.txt";

$content = "POST\n/oapi/v1/pagamentos/boleto/validarPagamento\n\n$bodyJson\n$accessToken\n$jti\n$xBradTimestamp\nSHA256";

$file = fopen($nameFile, 'w');

fwrite($file, $content);

fclose($content);

include_once("signatureValidaPagamento.php");