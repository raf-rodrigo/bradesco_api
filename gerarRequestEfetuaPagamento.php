<?php

require 'gerarJwt.php';
require 'gerarAccessToken.php';
// require 'consultaDadosBoleto.php';
require 'consultaDadosPagamentos.php';
require 'configuracao.php';

//OBRIGATÓRIO
$agencia = AGENCIA; //number - agência da conta de débito
$bancoContaDebitada = BANCOCONTADEBITADA;//number - código do banco da conta debitada
$agenciaContaDebitada = AGENCIACONTADEBITADA; //number - agência da conta debitada
$digitoAgenciaDebitada = DIGITOAGENCIADEBITADA; //number = digita da agência da conta debitada
$contaDebitada = CONTADEBITADA; // number - código da conta debitada
$digitoContaDebitada = AGENCIACONTADEBITADA;//number - digito da conta debitada
$dadosSegundaLinhaExtrato = DADOSSEGUNDALINHAEXTRATO;//alfa - histórico complementar / 2ª linha de extrato
$dataMovimento = DATAMOVIMENTO;//number - data de movimento no formato AAAAMMDD
$dataPagamento = DATAPAGAMENTO;//number - data do pagamento no formato AAAAMMDD
$identificacaoTituloCobranca = DADOSENTRADA;//munber - código de barra
$indicadorFormaCaptura = TIPOENTRADA; //number - indicador da forma de capture - Fixo "1"
$horaTransacao = $horarioTransacao;//hora da transação no formato HHMMSS
$indicadorFuncao = INDICADORFUNCAO; //indica afunção quea transação realizará - 1. PAGAEMENTO/AGENDAMENTO - FIXO "1"
$transactionId = TRANSACTIONID;//number - transaction id enviado pelo usuário - QUAL É ESSE VALOR -> ID DA TABELA TRANSAÇÃO?
// $numeroAutenticacao;

if ($response->consultaFatorDataVencimentoResponse->bancoTitulo == 237)
{
    $cpfCnpjRemetente = $response->consultaFatorDataVencimentoResponse->cnpjBeneficiario;//number - código do CPF/CNPJ do remetente do boleto
    $cpfCnpjPortador = $response->consultaFatorDataVencimentoResponse->cpfCnpjPagador;//number - código do CPF/CNPJ do portador do clinete
    $nomeCliente = "abc";//alfa - mone do cliente - MESMO DO SACADO?
    $valorTitulo = $response->consultaFatorDataVencimentoResponse->valorTitulo;//number - valor do boleto, corresponde ao valor pelo qual o cliente quitará o boleto
    $numeroControleParticipante = 0;//alfa - deve ser enviado para o pagamento de título de outros bancos. Nesse caso, enviar o conteúdo retornado no campo de mesmo nome da saída do passo 1. Para pagamento de títulos bradesco enviar "0"
    $dataVencimento = $response->consultaFatorDataVencimentoResponse->dataVencimento;//number - data de vencimento no formato AAAAMMDD
    
}else
{
    $cpfCnpjRemetente = $response->consultaDadosTituloCIPResponse->cpfCnpjCedente;//number - código do CPF/CNPJ do remetente do boleto
    $cpfCnpjPortador = $response->consultaDadosTituloCIPResponse->cpfCnpjSacado;//number - código do CPF/CNPJ do portador do clinete
    $nomeCliente = $response->consultaDadosTituloCIPResponse->nomeSacado;//alfa - mone do cliente - MESMO DO SACADO?
    $valorTitulo = $response->consultaDadosTituloCIPResponse->valorTitulo;//number - valor do boleto, corresponde ao valor pelo qual o cliente quitará o boleto
    $numeroControleParticipante = $response->consultaFatorDataVencimentoResponse->numeroControleParticipante;//alfa - deve ser enviado para o pagamento de título de outros bancos. Nesse caso, enviar o conteúdo retornado no campo de mesmo nome da saída do passo 1. Para pagamento de títulos bradesco enviar "0"
    $dataVencimento = $response->consultaDadosTituloCIPResponse->dataVencimento;//number - data de vencimento no formato AAAAMMDD
    
}

//NÃO OBRIGATÓRIO
$valorMinimoIdentificacao;//number - valor mínimo de identificação - Fixo "0"
$cpfCnpjDestinatario;//number - código do CPF/CNPJ do destinatário do boleto

$inJson = array(
        "agencia"=>$agencia,
        "pagamentoComumRequest"=>array(
                "contaDadosComum"=>array(
                    "agenciaContaDebitada"=>$agenciaContaDebitada,
                    "bancoContaDebitada"=>$bancoContaDebitada,
                    "contaDebitada"=>$contaDebitada,
                    "digitoAgenciaDebitada"=>$digitoAgenciaDebitada,
                    "digitoContaDebitada"=>$digitoContaDebitada
                ),
            "dadosSegundaLinhaExtrato"=>$dadosSegundaLinhaExtrato,
            "dataMovimento"=>$dataMovimento,
            "dataPagamento"=>$dataPagamento,
            "dataVencimento"=>$dataVencimento,
            "identificacaoTituloCobranca"=>$identificacaoTituloCobranca,
            "indicadorFormaCaptura"=>$indicadorFormaCaptura,
            "valorTitulo"=>$valorTitulo,
            "horaTransacao"=>$horaTransacao
        ),
        "destinatarioDadosComum"=>array(
            "cpfCnpjDestinatario"=>$cpfCnpjDestinatario
        ),
        "indicadorFuncao"=>$indicadorFuncao,
        "nomeCliente"=>$nomeCliente,
        "numeroControleParticipante"=>$numeroControleParticipante,
        "portadorDadosComum"=>array(
            "cpfCnpjPortador"=>$cpfCnpjPortador
        ),
        "remetenteDadosComum"=>array(
            "cpfCnpjRemetente"=>$cpfCnpjRemetente
        ),
        "transactionId"=>$transactionId,
        "valorMinimoIdentificacao"=>$valorMinimoIdentificacao
        // "numeroAutenticacao"=>$numeroAutenticacao
    );

$bodyJson = json_encode($inJson);

$nameFile = "requestEfetuaPagamento.txt";

$content = "POST\n/oapi/v1/pagamentos/boleto/efetivarPagamento\n\n$bodyJson\n$accessToken\n$jti\n$xBradTimestamp\nSHA256";

$file = fopen($nameFile, 'w');

fwrite($file, $content);

fclose($content);

include_once("signatuaEfetuaPagamento.php");