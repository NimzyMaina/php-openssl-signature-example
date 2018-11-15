<?php

$plainText  = "Test Encifioewiuof";
$plainText2  = "Test Enc";

$privateKey = openssl_pkey_get_private("file://".getcwd()."/private.pem");
$publicKey = openssl_pkey_get_public("file://".getcwd()."/public.pem");

$pubString = "-----BEGIN PUBLIC KEY-----".PHP_EOL."MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1BvVbYnuhGGmmIwUdUkFP+WG+tkXyf+o7DopD2MgDh+jwyvAjwDbSENHOwRuIzYEPBePk1lcchTDraz6VbWbwnDWJNn6cQkDCozRvuN1JnYa88Yyu7XFQyvskwpk2zgzJ3azuDYAZ0I4yBXAeamLXibOOjm9KFGrBhDMGUtQLVvayZTTiyyJnDXh5bNISjZeWU1VEiksaMUYujrXmLKDIlFM8xlJJvmvijlwS23J9oP3co3uHhd14pGXHKOYXvyVt3Q1taFIps7zS2x2vsGCaK9cdHrExWQdF9fzN95QfagMp7f2DSMQVhOsXTdZFXMOrkVtWOTlwUJucBGstKOjNwIDAQAB".PHP_EOL."-----END PUBLIC KEY-----";
// Make a signature
openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);
$signature = base64_encode($signature);
// Verify signature 0 for fail & 1 for success
$res = openssl_verify($plainText,base64_decode($signature),$pubString,OPENSSL_ALGO_SHA256);

echo '<pre>';
var_dump($res);
exit;


