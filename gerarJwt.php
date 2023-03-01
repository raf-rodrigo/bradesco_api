<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require 'vendor/autoload.php';

$privateKey = <<<EOD
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDMtkx837miJBnA
vEurlBAenz6LYwkfsdfsMbw/72nQPVoduDC+zf4cmlsZHVUS+1t1XDpBvNC102Ue
y52dOIMaNhwuYWIpGCQ0PdNYe390+EGh8gmMzCXGWX593DAoWWLQ1kNF2+pl84va
b/BE/vabPfKqsqOmwxBPJBDX9SRavk8dUNo8CrQVa3gRt2GSBPW+1PSHvfYr+f+9
VDJ9HzgEpmj7RXEbEfaQnV9Km/FFswdIYnEzbspMocZtarImcxnp72XiOiCcQGiG
xddbF66mZccK9/8nBQuoWqY9r2oXf3UqpLxyw7Xw17rat3OCiEu/S5gQ4rJG7WDs
pJ/XP1K3AgMBAAECggEACVFW4+wu/+FSxsILU7D2lt46s497b9Thdh/BaMuV7mOM
gYPLvDOWGGkyMk9yet20CQB8qldUzKHlnWQ5NVnIJd5GpBnMpQAzTaluwD1GzOUr
35orsxvrsD6Mkl/+VSfUZpsKDtj2r1NJj1S/A/Ty9pf4PqrI5iNEz8Vdeubuk662
8vJ1zy2Doq10HOP2ER6B1dwUEKA6KfulNj+vd8chVet+69gojJ+S9osIkf6plRJj
O/w0Qxk+jQACvKw8qpU1UJ84MpSDlzUMu31Ue90LxUwH/meEdBmDEcjtLjF3Jr6E
kGlAm5e0hHpGu/3SFyxhiulVMKyP5Xile/GU5qiuAQKBgQD5MAQZyONGg7gE9TAP
C+XXgUXp2KwWabh7km/wstnBdG+EsyFXm/vvHzJbWPtnVnflokEcjI70eVWmxZh0
STfEpi/V1ok63pKQKR+1o2uSKRbvXGPev6jOs410hdeuPPOYZzlzFtiXQ6Z5+GDY
Trah8P11ZsagtyWTjibU+OhDAQKBgQDSTwNlmQ78RVBYkEr+q9U7N00jgtj+YN3y
4kwJidrzgy8ufJHqOoFsyTY6Af+py+A438NsOez+6Xn5UkLkzIH4rghwZTnD3bh9
85INXJJ7jFfqGIWioAEVSDf08pLgew61+p3rJHO8OchdGIdYQ3vIPysytlE6UrdU
JCDc9LBttwKBgGyL4f1paLdawVpHg/vJ7wEFKvNGkw/gVaBelax//CMtGTbw4OFM
7V2odUMda8YEAI5jM4HAKLWN9SWEhXiCPGzJB6VaosSmF6n8f/ebjExK4da6pDZv
vpTr2f6cHuujWWlV7cyYcxjROz9+VpFjGGNKmt39OyDfJtsz3O6AzowBAoGBAItn
Bnhk0VMU2uIpmxxeaDQdPwuH4zASo2wo5n/4GeMKFM86kTnyV/H1GfWvd3lkg5cP
c6kcaGS7/DbweRjSGmDtcviVkImtQWpzdl9W+l3ctDWe1Y3rg2xI95Zs6EDWMPUd
T2obOoAMySw0Yxp1eOpOPOMbiJIhXBXw8LulkgDXAoGAAemH3kOdMeyjEhYlkE3a
oZppUbRexsiQYXpFTzKAGMK4uoKeqPZpmqIKk8pOOz2cKHyHgrq/Drt9NoEIejd5
dSUFnFzGDRFIihdheXRA845lHhM7fHuXTM5EUCRF9NZyHQSQ5KuiKliBttGPFwl4
w75VN0prMsEo2JJMQorSvIU=
-----END PRIVATE KEY-----
EOD;

$publicKey = <<<EOD
-----BEGIN CERTIFICATE-----
MIIDbTCCAlUCFDH9YPPh6gO14KRYGEBgXiZWtVhrMA0GCSqGSIb3DQEBCwUAMHMx
EzARBgNVBAgMClPDo28gUGF1bG8xHTAbBgNVBAoMFENhc2EgZG8gQ3LDqWRpdG8g
Uy9BMTAwLgYDVQQDDCdDYXNhIGRvIENyw6lkaXRvIFMvQSA6IDA1NDQyMDI5LzAw
MDEtNDcxCzAJBgNVBAYTAkJSMB4XDTIzMDExNzE0NTEyMFoXDTI2MDEwMTE0NTEy
MFowczETMBEGA1UECAwKU8OjbyBQYXVsbzEdMBsGA1UECgwUQ2FzYSBkbyBDcsOp
ZGl0byBTL0ExMDAuBgNVBAMMJ0Nhc2EgZG8gQ3LDqWRpdG8gUy9BIDogMDU0NDIw
MjkvMDAwMS00NzELMAkGA1UEBhMCQlIwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAw
ggEKAoIBAQDMtkx837miJBnAvEurlBAenz6LYwkfsdfsMbw/72nQPVoduDC+zf4c
mlsZHVUS+1t1XDpBvNC102Uey52dOIMaNhwuYWIpGCQ0PdNYe390+EGh8gmMzCXG
WX593DAoWWLQ1kNF2+pl84vab/BE/vabPfKqsqOmwxBPJBDX9SRavk8dUNo8CrQV
a3gRt2GSBPW+1PSHvfYr+f+9VDJ9HzgEpmj7RXEbEfaQnV9Km/FFswdIYnEzbspM
ocZtarImcxnp72XiOiCcQGiGxddbF66mZccK9/8nBQuoWqY9r2oXf3UqpLxyw7Xw
17rat3OCiEu/S5gQ4rJG7WDspJ/XP1K3AgMBAAEwDQYJKoZIhvcNAQELBQADggEB
AFn9PqZowOyC6Q/DWvNyxjr2WEAq5Q6gGJMZw7rnVica11u900TZg/v0r0mB1EmK
1dlHHMFq8Z2pgcz8TW4yjTRYoRsOtUGh8ismBYNowZ6qiR0K5SbpIMXhcdpgQm85
PAtW9diQC/jjfmgx+oRG1CLi3E9gZ4XqA7K2NUyhrduI4IsrPIi6Jw814DAwVUnM
ryJ7y2WdEU9otIPpeHbEcQIS8pJqCSYEufrVAtLpVOvCwQS/7YyFtiAQ+4ERTqsS
w3INh0dTWxGhoKxuX7yxIySgJAjZrGgxdsU2uW/iz7KxHB/sAXy0IG2ZQDTS0IWd
fX/urx1eLo7aceFcPJ/6UP8=
-----END CERTIFICATE-----
EOD;

$theDateTime = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

$xBradTimestamp = $theDateTime->format('Y-m-d\TH:i:s\-00:00');

$iatI = $theDateTime->format('U');
$iat = "$iatI";
$expI = $theDateTime->add(new DateInterval('PT1H'))->format('U');
$exp = "$expI";
$jtiI = round(microtime(true)*1000);
$jti = "$jtiI";

// echo $xBradTimestamp.PHP_EOL;
// echo $iat.PHP_EOL;
// echo $exp.PHP_EOL;
// echo $jti.PHP_EOL;

$payload = array(
    "aud"=>"https://proxy.api.prebanco.com.br/auth/server/v1.1/token",
    "sub"=>"8f99b0e0-802b-46a1-b37b-bc3c19563af6",
    "iat"=>$iat,
    "exp"=>$exp,
    "jti"=>$jti,
    "ver"=>"1.1"
);

$jwt = JWT::encode($payload, $privateKey, 'RS256');

$decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));

// echo $jwt;