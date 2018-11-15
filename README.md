# PHP Open SSL Signature Example (Sign & Verify)

This example shows how to make and verify a signature using the Openssl Protocal.

## Make a signature

```php
<?php
$data_string = '{
    "countryCode":"KE",
    "amount": 10000,
    "accountId":"12394569",
    "date":"2018-08-09"
    }';
$token      = "QNg9X7cLJSpZVOpaJJ33wX0AbcRF";
$plainText  = "KE123945692018-08-09"; // CONCAT OF DATA STRING
// Get the private key
$privateKey = openssl_pkey_get_private("file://".getcwd()."/private.pem");
// Or you can just use a string
// $privateKey = "-----BEGIN RSA PRIVATE KEY-----".PHP_EOL."YOUR-PRIVATE-KEY-HERE".PHP_EOL."-----END RSA PRIVATE KEY-----";
openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);

$curl        = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://example.com/api/v1/test",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data_string,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer " . $token,
        "cache-control: no-cache",
        "Content-Type: application/json",
        "signature: " . base64_encode($signature)
    )
));
$result = curl_exec($curl);
$err    = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $result;
}

```

## Verify the signature on the server

```php
<?php

// Get header
$headers = apache_request_headers(); // This method is specific for Apache server
$signature = $headers['signature'];
// Get the public key setup during the integration process Eg. Like how its done on Github settings
$publicKey = openssl_pkey_get_public("file://".getcwd()."/public.pem");
// Or just is a string
// $pubString = "-----BEGIN PUBLIC KEY-----".PHP_EOL."PUBLIC-KEY".PHP_EOL."-----END PUBLIC KEY-----";

$plainText  = $_POST['countryCode'].$_POST['accountId'].$_POST['date'];

$res = openssl_verify($plainText,base64_decode($signature),$pubString,OPENSSL_ALGO_SHA256);

if($res == 1){
    // The signature is valid
    echo 'Your request has been processed';
}else{
    // The signature is not valid
    echo 'Unable to authenticate request';
}

```